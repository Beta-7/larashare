<!DOCTYPE html>
<html>
<head>
    <title>A user has shared a download with you</title>
</head>
<body>
<h1>{{$details['sharedBy']}} has shared {{$details['name']}} with you.</h1>
<h1>Press <a href="{{route('download',['fileId'=>$details['fileId']])}}">here</a> to download it.</h1>

</body>
</html>