function pointstable(points){

    var res=`<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Title</th>
      <th scope="col">Start</th>
      <th scope="col">Stop</th>
      <th scope="col">Lat</th>
      <th scope="col">Lon</th>
      <th scope="col">Radius</th>
      <th scope="col">Timer</th>
    </tr>
  </thead>
  <tbody>`;

  $.each(points, function( index, value ) {
    
 

  res += `<tr>
      <th scope="row">`+(index+1)+`</th>
      <td>`+(value.title)+`</td>
      <td>`+(value.start)+`</td>
      <td>`+(value.stop)+`</td>
      <td>`+(value.lat)+`</td>
      <td>`+(value.lon)+`</td>
      <td>`+(value.radius)+`</td>
      <td>`+(value.timer)+`</td>
    </tr>`;
});
  res += `</tbody>
</table>`;
return res;
}