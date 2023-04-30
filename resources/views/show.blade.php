<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ぐるっと - {{$restaurant['name']}}</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOSJHB-KVR4lKLbihxhDEZhy7snKruUUY&callback=initMap"></script>
    @vite('resources/css/app.css')
</head>

<body>

    <header class="bg-gray-700">
        <a href="/">
            <h1 class="text-3xl font-bold text-gray-100">ぐるっと<i class="fas fa-utensils" style="color: #ffffff;"></i></h1>
        </a>
    </header>

    <div class="bg-white">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-8">
                <div class="aspect-w-4 aspect-h-3 sm:overflow-hidden sm:rounded-lg">
                    <img src="{{$restaurant['photo']['pc']['l']}}" alt="{{$restaurant['name']}}のサムネイル" class="h-full w-full object-cover object-center">
                </div>

                <div class="space-y-12">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{$restaurant['name']}}</h1>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">所在地</h3>
                        <h4>{{$restaurant['address']}}</h4>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-900">営業時間</h3>
                        <h4>{{$restaurant['open']}}</h4>
                    </div>
                </div>
            </div>
            <div id="map" class="w-full h-96 mb-8 rounded-lg"></div>
            <div class="text-center pb-8">
                <a href="/">
                    <button type="submit" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        検索画面に戻る
                    </button>
                </a>
            </div>
        </div>
    </div>
    <script>
        function initMap() {
            const restaurantLatLng = {
                lat: parseFloat("{{$restaurant['lat']}}"),
                lng: parseFloat("{{$restaurant['lng']}}")
            };
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 16,
                center: restaurantLatLng,
            });

            const marker = new google.maps.Marker({
                position: restaurantLatLng,
                map: map,
            });
        }
    </script>
</body>

</html>