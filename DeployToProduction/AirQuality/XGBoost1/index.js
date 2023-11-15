async function runExample() {

    var x = new Float32Array( 1, 12 )

    var x = [];

     x[0] = document.getElementById('box1').value;


    let tensorX = new ort.Tensor('float32', x, [1, 1] );
    let feeds = {float_input: tensorX};

    let session = await ort.InferenceSession.create('xgboost_Classification_AirQuality_ort.onnx');
    
   let result = await session.run(feeds);
   let outputData = result.variable.data;

  outputData = parseFloat(outputData).toFixed(2)

   let predictions = document.getElementById('predictions');

  predictions.innerHTML = ` <hr> Got an output tensor with values: <br/>
   <table>
     <tr>
       <td>  Rating of Air Quality  </td>
       <td id="td0">  ${outputData}  </td>
     </tr>
  </table>`;
    


}
