<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Form Rumah Sakit</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
  /* .row:after {
            content: "";
            display: table;
            clear: both;
        } */
    .container {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }
  input[type="text"],
  textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
  }
  input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  input[type="submit"]:hover {
    background-color: #45a049;
  }
  body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
  }

  h2 {
    text-align: center;
    margin-bottom: 20px;
  }

  .form-container, .table-container, .map-container {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 600px;
    margin-bottom: 20px;
  }

  label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
  }

  input {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  button {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  button:hover {
    background-color: #0056b3;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  th, td {
    padding: 10px;
    border: 1px solid #ccc;
    text-align: left;
  }

  .delete-button {
    background-color: #dc3545;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
  }

  .delete-button:hover {
    background-color: #c82333;
  }

  #map {
    height: 400px;
    margin-bottom: 20px;
  }
</style>
</head>
<body>

  <h2>Masukkan Nama Rumah Sakit</h2>

  <div class="map-container">
    <div id="map"></div>
  </div>

  <div class="form-container">
    <form id="hospital-form" action="{{ route('store-hospital') }}" method="POST">
      @csrf
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>
      
      <label for="latitude">Latitude:</label>
      <input type="text" id="latitude" name="latitude" required>
      
      <label for="longitude">Longitude:</label>
      <input type="text" id="longitude" name="longitude" required>
      
      <label for="address">Address:</label>
      <textarea id="address" name="address" required></textarea>
      
      <button type="submit">Submit</button>
    </form>
  </div>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Rumah Sakit</th>
          <th>Latitude</th>
          <th>Longitude</th>
          <th>Alamat</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="hospital-table-body">
        @foreach ($hospitals as $index => $hospital)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $hospital->name }}</td>
            <td>{{ $hospital->latitude }}</td>
            <td>{{ $hospital->longitude }}</td>
            <td>{{ $hospital->address }}</td>
            <td><button class="delete-button" onclick="deleteHospital(this, {{ $hospital->id }})">Delete</button></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    var map = L.map('map').setView([-8.551, 115.401], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var markers = [];

    @foreach ($hospitals as $hospital)
      var marker = L.marker([{{ $hospital->latitude }}, {{ $hospital->longitude }}]).addTo(map)
        .bindPopup(`<b>{{ $hospital->name }}</b><br>{{ $hospital->address }}`);
      markers.push(marker);
    @endforeach

    function deleteHospital(button, id) {
      const row = button.parentNode.parentNode;
      const index = row.rowIndex - 1;

      // Remove marker from map
      map.removeLayer(markers[index]);
      markers.splice(index, 1);

      row.parentNode.removeChild(row);
      updateRowNumbers();

      // Send delete request to the server
      fetch(`/delete-hospital/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      }).then(response => {
        if (response.ok) {
          console.log('Hospital deleted successfully');
        } else {
          console.error('Failed to delete hospital');
        }
      });
    }

    function updateRowNumbers() {
      const tableBody = document.getElementById('hospital-table-body');
      for (let i = 0; i < tableBody.rows.length; i++) {
        tableBody.rows[i].cells[0].innerHTML = i + 1;
      }
    }
  </script>

</div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  var map = L.map('map').setView([-8.551, 115.401], 10);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  }).addTo(map);

  var hospitalNames = [
    "Rumah Sakit Meika",
    "Rumah Sakit Denpasar",
    "Rumah Sakit Klungkung",
    "Rumah Sakit Karangasem",
    "Rumah Sakit Singaraja"
  ];

  formatContent = function(lat, lng, index, hospitalName) {
    return `
      <div class="wrapper">
        <div class="row">
          <div class="cell merged" style="text-align:center">Marker [ ${index+1} ]</div>
        </div>
        <div class="row">
          <div class="col">Nama Rumah Sakit</div>
          <div class="col2">${hospitalName}</div>
        </div>
        <div class="row">
          <div class="col">Latitude</div>
          <div class="col2">${lat}</div>
        </div>
        <div class="row">
          <div class="col">Longitude</div>
          <div class="col2">${lng}</div>
        </div>
        <div class="row">
          <div class="col">Left click</div>
          <div class="col2">New marker / show popup</div>
        </div>
        <div class="row">
          <div class="col">Right click</div>
          <div class="col2">Delete marker</div>
        </div>
      </div>
    `;
  }

  var markers = [];

  map.on('click', function(e) {
    var randomHospitalName = hospitalNames[Math.floor(Math.random() * hospitalNames.length)];
    var newMarker = L.marker(e.latlng).addTo(map).bindPopup(formatContent(e.latlng.lat, e.latlng.lng, markers.length, randomHospitalName));
    markers.push(newMarker);
  });
</script>
</body>
</html>
