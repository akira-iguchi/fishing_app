let map //変数の定義
let geocoder //変数の定義
const SpotLat = document.getElementById( "spot_latitude" );
const SpotLng = document.getElementById( "spot_longitude" );

function initMap(){ //コールバック関数
  geocoder = new google.maps.Geocoder() //GoogleMapsAPIジオコーディングサービスにアクセス
  const getMap = document.getElementById('map');

  if(getMap){ //'map'というidを取得できたら実行
    map = new google.maps.Map(getMap, { //'map'というidを取得してマップを表示
      center: {lat: 35.6594666, lng: 139.7005536}, //最初に表示する場所（今回は「渋谷スクランブル交差点」が初期値）
      zoom: 15, //拡大率（1〜21まで設定可能）
    });

    marker = new google.maps.Marker({ //GoogleMapにマーカーを落とす
      position: map.center,
      draggable: true,
      disableDoubleClickZoom: true,
      scaleControl: true,
      map: map //マーカーを落とすマップを指定
    });

    google.maps.event.addListener(marker, 'dragend', function(e) {
      //イベントの引数eの、プロパティ.latLngがmarkerの緯度経度。
      SpotLat.value = e.latLng.lat(),
      SpotLng.value = e.latLng.lng(),
      map.panTo(marker.position);
    });

    // google.maps.event.addListener(map, 'click', function(e) {
    //   //marker作成
    //   let marker = new google.maps.Marker({draggable: true});
    //   //markerの位置を設定
    //   //event.latLng.lat()でクリックしたところの緯度を取得
    //   marker.setPosition(new google.maps.LatLng(e.latLng.lat(), e.latLng.lng()));
    //   //marker設置
    //   marker.setMap(map);
    //   SpotLat.value = e.latLng.lat(),
    //   SpotLng.value = e.latLng.lng(),
    //   map.panTo(marker.position);
    // });

  }else{ //'map'というidが無かった場合
    map = new google.maps.Map(document.getElementById('show_map'), { //'show_map'というidを取得してマップを表示
      center: {lat: lat, lng: lng}, //controllerで定義した変数を緯度・経度の値とする（値はDBに入っている）
      zoom: 15, //拡大率（1〜21まで設定可能）
    });

    marker = new google.maps.Marker({ //GoogleMapにマーカーを落とす
      position:  {lat: lat, lng: lng}, //マーカーを落とす位置を決める（値はDBに入っている）
      map: map //マーカーを落とすマップを指定
    });
  }
}

function codeAddress(){ //コールバック関数

  let inputAddress = document.getElementById('address').value; //'address'というidの値（value）を取得

  geocoder.geocode( { 'address': inputAddress}, function(results, status) { //ジオコードしたい住所を引数として渡す
    if (status == 'OK') {
      let lat = results[0].geometry.location.lat(); //ジオコードした結果の緯度
      let lng = results[0].geometry.location.lng(); //ジオコードした結果の経度
      let mark = {
          lat: lat, //緯度
          lng: lng  //経度
      };

      map.setCenter(results[0].geometry.location); //最も近い、判読可能な住所を取得したい場所の緯度・経度
      let marker = new google.maps.Marker({
          map: map, //マーカーを落とすマップを指定
          draggable: true,
          disableDoubleClickZoom: true,
          scaleControl: true,
          position: results[0].geometry.location //マーカーを落とす位置を決める
      });

      google.maps.event.addListener(marker, 'dragend', function(e) {
        //イベントの引数eの、プロパティ.latLngがmarkerの緯度経度。
        SpotLat.value = e.latLng.lat(),
        SpotLng.value = e.latLng.lng(),
        map.panTo(marker.position);
      });

      SpotLat.value = results[0].geometry.location.lat(),
      SpotLng.value = results[0].geometry.location.lng()
    } else {
      alert('該当する結果がありませんでした');
    }
  });
}