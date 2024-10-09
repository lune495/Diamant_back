<!DOCTYPE html>
<html>
<head>
    <title>Barcodes PDF</title>
    <style>
       .barcode {
            text-align: center; /* Centrer le contenu */
            margin-bottom: 20px;
        }
        .barcode img {
            display: block;
            margin: 0 auto; /* Centrer l'image */
        }
        .barcode p {
            margin: 0; /* Supprimer les marges */
        }
    </style>
</head>
<body>
    @foreach($barcodeImages as $barcodeImage)
        <div class="barcode">
            <!-- <p>Barcode: {{ $barcodeImage['code'] }}</p> -->
            <img src="{{ $barcodeImage['image'] }}" alt="barcode" />
            <p>100004</p>
        </div>
    @endforeach
</body>
</html>