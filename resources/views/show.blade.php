<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ぐるっと - {{$restaurant['name']}}</title>
</head>

<body>
    <h1>{{$restaurant['name']}}</h1>
    <img src="{{$restaurant['photo']['pc']['l']}}" alt="{{$restaurant['name']}}のサムネイル">
    <p>住所：{{$restaurant['address']}}</p>
    <p>営業時間：{{$restaurant['open']}}</p>
    <a href="/">一覧ページに戻る</a>
</body>

</html>