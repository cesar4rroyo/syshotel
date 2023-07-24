<div class="flex space-x-6">
    <input placeholder="{{ $placeholder }}" name="{{ $name }}" id="search" type="text" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full p-2.5" onkeypress="handleChangeSearch()" onchange="handleChangeSearch()" onblur="handleChangeSearch()">
</div>
<div class="flex space-x-6" id="divContainer">
    <div class=" w-full bg-blue-100" id="divSearch"></div>
</div>
<script>
    function handleChangeSearch()
    {
        var param = document.getElementById('search').value;
        var container = document.getElementById('divContainer');
        var divListSearch = document.getElementById('divSearch');
        if(param.length < 2) {
            divListSearch.innerHTML = '';
            divListSearch.innerHTML = 'No se encontraron resultados';
            return;
        }
        var url = '{{ $route }}';
        var divListSearch = document.getElementById('divSearch');
        var propName = '{{ $propName }}';
        var propOnClickName = '{{ $propOnClick }}';
        var branchId = document.getElementById('originbranch') ? document.getElementById('originbranch').value : null;
        axios.get(url, {
            params: {
                param: param,
                branchId: branchId
            }
        }).then(function (res){
            var data = res.data.data;
            if (data == 0) {
                divListSearch.innerHTML = '';
                divListSearch.innerHTML = 'No se encontraron resultados';
                return;
            }else{
                container.style.height = '100px';
                divListSearch.innerHTML = '';
                var ul = document.createElement('ul');
                ul.setAttribute('class', 'w-full');
                data.forEach(element => {
                    var li = document.createElement('li');
                    li.setAttribute('class', 'w-full flex justify-between items-center border-b border-gray-300');
                    var div = document.createElement('div');
                    div.setAttribute('class', 'flex items-center');
                    var span = document.createElement('span');
                    span.setAttribute('class', 'text-sm text-gray-900');
                    span.innerHTML = element[propName];
                    div.appendChild(span);
                    li.appendChild(div);
                    ul.appendChild(li);
                    var paramsOnClickFunction = "'" + element.id + "' , '" + element[propName] + "'";
                    if(element.stock){
                        paramsOnClickFunction += " , '" + element.stock + "'";
                    }
                    li.setAttribute('onclick', propOnClickName + '(' + paramsOnClickFunction + ')');
                });
                divListSearch.appendChild(ul);
                divListSearch.setAttribute('class', 'w-full bg-blue-100 overflow-y-auto');
            }
        }).catch(function (err){
            console.log(err);
        });
    }
</script>