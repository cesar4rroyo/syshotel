<?php

namespace App\Http\Services;

use App\Librerias\Libreria;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BookingService
{
    private Booking $booking;
    private Libreria $libreria;
    private float $checkin_hour;
    private float $checkin_minute;
    private float $checkout_hour;
    private float $checkout_minute;
    private int $year;
    private int $branch_id;
    private int $business_id;
    private $today;
    private $firstDayOfYear;

    public function __construct(int $branch_id, int $business_id)
    {
        $this->booking = new Booking();
        $this->libreria = new Libreria();
        $this->checkin_hour = (float) config('constants.checkin_hour');
        $this->checkin_minute = (float) config('constants.checkin_minute');
        $this->checkout_hour = (float) config('constants.checkout_hour');
        $this->checkout_minute = (float) config('constants.checkout_minute');
        $this->year = (int) date('Y');
        $this->branch_id = $branch_id;
        $this->business_id = $business_id;
        $this->today = Carbon::now()->toDateString();
        $this->firstDayOfYear = Carbon::createFromDate($this->year, 1, 1)->toDateString();
    }

    public function getDataToCalendar(array $statusBooking, array $statusRooms): Collection
    {
        $rooms = Room::with('processes')->search(null, $this->branch_id, $this->business_id, $statusRooms)->get();
        $bookings = $this->booking->search(null, $this->firstDayOfYear, null, null, $this->branch_id, $this->business_id, $statusBooking)->get();
        $data = collect();
        foreach ($rooms as $room) {
            $data->push([
                'process_id' => $room->processes->first()->id,
                'number' => $room->number,
                'date' => $room->processes->first()->date,
                'start' => $room->processes->first()->start_date,
                'end' => $room->processes->first()->end_date,
                'title' => 'Habitación ' . $room->name . ' - ' . $room->processes->first()->client->name,
                'status' => $room->status,
                'client' => $room->processes->first()->client->name,
                'type' => 'room',
            ]);
        }
        foreach ($bookings as $booking) {
            $data->push([
                'process_id' => $booking->id,
                'number' => $booking->room->number,
                'date' => $booking->date,
                'start' => $booking->datefrom,
                'end' => $booking->dateto,
                'title' => 'Reserva Habitación ' . $booking->room->name . ' - ' . $booking->client->name,
                'status' => $booking->status,
                'client' => $booking->client->name,
                'type' => 'booking',
            ]);
        }
        return $data;
    }

    public function storeBooking(array $data): Booking
    {
        $booking = $this->booking->create([
            'date' => $this->today,
            'number' => $data['number'],
            'datefrom' => $data['datefrom'],
            'dateto' => $data['dateto'],
            'status' => 'P',
            'room_id' => $data['room_id'],
            'user_id' => auth()->user()->id,
            'days' => $data['days'],
            'amount' => $data['amount'],
            'notes' => $data['notes'],
            'client_id' => $data['client_id'],
            'branch_id' => $this->branch_id,
            'business_id' => $this->business_id,
        ]);
        return $booking;
    }

    public function updateBooking(array $data, Booking $booking): Booking
    {
        $datefrom = $this->libreria->formatDateWithAddingTime($data['datefrom'], null, null, null, $this->checkin_hour, $this->checkin_minute, null)->toDateString();
        $dateto = $this->libreria->formatDateWithAddingTime($data['dateto'], null, null, null, $this->checkout_hour, $this->checkout_minute, null)->toDateString();
        $booking->update([
            'datefrom' => $datefrom,
            'dateto' => $dateto,
            'days' => $this->libreria->getDaysBetweenDates($datefrom, $dateto),
            'amount' => $data['amount'],
            'notes' => $data['notes'],
            'client_id' => $data['client_id'],
            'status' => $data['status'],
            'room_id' => $data['room_id'],
        ]);
        return $booking;
    }

    public function deleteBooking(Booking $booking): void
    {
        $booking->update([
            'status' => Booking::CANCELLED_STATUS,
        ]);
    }

    public function getAvailableRooms($dateFrom, $dateTo)
    {
        $rooms = Room::with('roomType')->available($dateFrom, $dateTo, $this->branch_id, $this->business_id)->get();
        $rooms = $rooms->map(function ($room) {
            return [
                'id' => $room->id,
                'name' => $room->name,
                'number' => $room->number,
                'type' => $room->roomType->name,
                'price' => 'S/. ' . $room->roomType->price,
            ];
        });
        return collect($rooms);
    }
}
