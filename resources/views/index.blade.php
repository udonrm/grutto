<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ぐるっと</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <style>
        table,
        #pagination,
        #loading {
            display: none;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-50">

    <header class="bg-gray-700">
        <a href="/">
            <h1 class="text-3xl font-bold text-gray-100">ぐるっと<i class="fas fa-utensils" style="color: #ffffff;"></i></h1>
        </a>
    </header>

    <div class="flex justify-center">
        <label for="rangeSelect">検索範囲：</label>
        <select id="rangeSelect">
            <option value="1">300m</option>
            <option value="2">500m</option>
            <option value="3">1000m</option>
            <option value="4">2000m</option>
            <option value="5" selected>3000m</option>
        </select>
    </div>

    <div class="flex justify-center">
        <button id="searchButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            現在地から検索
        </button>
    </div>

    <div class="flex justify-center">
        <div id="loading" class="w-16 h-16 border-t-4 border-gray-500 border-solid rounded-full animate-spin"></div>
    </div>

    <table class="mx-auto w-full text-lg text-left text-gray-600 ">
        <thead>
            <tr>
                <th scope="col" class="px-6 py-3">店名</th>
                <th scope="col" class="px-6 py-3">アクセス</th>
                <th scope="col" class="px-6 py-3">お店の雰囲気</th>
            </tr>
        </thead>
        <tbody id="restaurantList">
        </tbody>
    </table>

    <div id="pagination" class="flex justify-center">
        <a href="#" id="prevPage" class="inline-flex items-center px-4 py-2 text-xl font-medium text-blue-500 hover:text-blue-700">&lt; 前へ</a>
        <span id="currentPage" class="inline-block min-w-[3rem] text-center text-xl text-gray-600"></span>
        <a href="#" id="nextPage" class="inline-flex items-center px-4 py-2 text-xl font-medium text-blue-500 hover:text-blue-700">次へ &gt;</a>
    </div>

    <script>
        let currentPage = 1;
        let latitude;
        let longitude;
        let range;

        function searchRestaurants(range, currentPage) {
            const requestData = {
                lat: latitude,
                lng: longitude,
                range: range,
                page: currentPage,
            };
            $.ajax({
                url: '/search',
                type: 'GET',
                dataType: 'json',
                data: requestData,
                data: {
                    lat: latitude,
                    lng: longitude,
                    range: range,
                    page: currentPage
                },
                success: function(data) {
                    $('#restaurantList').empty();
                    const restaurants = data.shop;
                    restaurants.forEach(restaurant => {
                        $('#restaurantList').append(`
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td  class="px-6 py-4"><a href="/detail/${restaurant.id}" class="hover:text-blue-500 transition">${restaurant.name}</a></td>
                                <td  class="px-6 py-4">${restaurant.access}</td>
                                <td  class="px-6 py-4"><img src="${restaurant.photo.pc.l}" alt="${restaurant.name}のサムネイル"></td>
                            </tr>
                        `);
                    });
                    $('#currentPage').text(currentPage);

                    const totalPages = data.total_pages;

                    if (currentPage <= 1) {
                        $('#prevPage').hide();
                    } else {
                        $('#prevPage').show();
                    }

                    if (currentPage >= totalPages) {
                        $('#nextPage').hide();
                    } else {
                        $('#nextPage').show();
                    }
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }

        function getCurrentPosition() {
            return new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        resolve(position);
                    },
                    error => {
                        reject(error);
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            });
        }

        function showLoading() {
            $('#loading').css('display', 'block');
        }

        function hideLoading() {
            $('#loading').css('display', 'none');
        }

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }


        $(document).ready(function() {
            $('#searchButton').click(async function() {
                try {
                    showLoading();

                    const position = await getCurrentPosition();
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;
                    range = $('#rangeSelect').val();

                    $('table, #pagination').show();

                    searchRestaurants(range, currentPage);

                } catch (error) {
                    console.error('Geolocation API error:', error);
                    alert('位置情報の取得に失敗しました。');
                } finally {
                    hideLoading(); // Hide loading animation
                }
            });

            $('#rangeSelect').change(function() {
                if (latitude && longitude) {
                    range = $('#rangeSelect').val();
                    searchRestaurants(range, currentPage);
                }
            });

            $('#prevPage').click(function(event) {
                event.preventDefault();
                if (currentPage > 1) {
                    currentPage--;
                    searchRestaurants(range, currentPage);
                }
            });

            $('#nextPage').click(function(event) {
                event.preventDefault();
                currentPage++;
                searchRestaurants(range, currentPage);
                scrollToTop();
            });
        });
    </script>
</body>

</html>