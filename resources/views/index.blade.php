<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ぐるっと</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h1>ぐるっと</h1>
    <label for="rangeSelect">検索範囲：</label>
    <select id="rangeSelect">
        <option value="1">300m</option>
        <option value="2">500m</option>
        <option value="3">1000m</option>
        <option value="4">2000m</option>
        <option value="5" selected>3000m</option>
    </select>
    <button id="searchButton">現在地から検索</button>
    <table>
        <thead>
            <tr>
                <th>店名</th>
                <th>アクセス</th>
                <th>サムネイル</th>
            </tr>
        </thead>
        <tbody id="restaurantList">
        </tbody>
    </table>
    <div id="pagination">
        <a href="#" id="prevPage">&lt; 前へ</a>
        <span id="currentPage"></span>
        <a href="#" id="nextPage">次へ &gt;</a>
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
                page: currentPage
            };
            console.log('Request data:', requestData);
            $.ajax({
                url: '/search',
                type: 'GET',
                dataType: 'json',
                cache: false,
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
                            <tr>
                                <td><a href="/detail/${restaurant.id}">${restaurant.name}</a></td>
                                <td>${restaurant.access}</td>
                                <td><img src="${restaurant.photo.pc.l}" alt="${restaurant.name}のサムネイル"></td>
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

        $(document).ready(function() {
            $('#searchButton').click(async function() {
                try {
                    const position = await getCurrentPosition();
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;
                    range = $('#rangeSelect').val();

                    searchRestaurants(range, currentPage);

                } catch (error) {
                    console.error('Geolocation API error:', error);
                    alert('位置情報の取得に失敗しました。');
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
            });
        });
    </script>
</body>

</html>