setInterval(() => loadData(), 10000);
loadData();

function loadData() {
    var req = new XMLHttpRequest();
    req.open('GET', 'https://api.darktornado.net/subway/west'); //Back-End URL
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    //req.setRequestHeader('Cache-Control', 'no-cache');
    req.send();
    req.onreadystatechange = function(e) {
        if (this.readyState == 4 && req.status === 200) {
            data = (req.responseText + '').trim();
            if (data != '') {
                applyData(data);
            }
        } else {
            //alert(e);
        }
    };
}

function applyData(data) {
    var src = '';
    data = JSON.parse(data);
    data.forEach((e) => {
        src += '<tr>';
        src += '<td class=train>' + (e.up ? '<img src="./images/metro_up.png">' : '↑') + '</td>';
        src += '<td class=line></td>';
        src += '<td class=train>' + (e.down ? '<img src="./images/metro_down.png">' : '↓') + '</td>';
        src += '<td class=station>' + e.sta + '</td>';
        src += '</tr>';
    });
    document.getElementById('data').innerHTML = src;
}