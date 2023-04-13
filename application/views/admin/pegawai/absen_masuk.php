<script>
  function initMap() {
    var locations = <?php echo json_encode( $mapHolder ); ?>;
    var maps = [];
    for (var i = 0; i < locations.length; i++) {

      var latlng = new google.maps.LatLng(locations[i][0][0], locations[i][0][1]);
      var map = new google.maps.Map(document.getElementById(‘map’ + i), {
        center: latlng,
        zoom: 13,
        scrollwheel: false,
        mapTypeControl: false,
        draggable: true
      });
      maps.push(map);

      for (var j = 0; j < locations[i].length; j++) {

        if(j != 0 ) {
          var loc = new google.maps.LatLng(locations[i][j][0], locations[i][j][1]);
          var marker = new google.maps.Marker({
            position: loc,
            map: map
          });
        }
        
      }
    }
  }
  
  initMap();
</script>