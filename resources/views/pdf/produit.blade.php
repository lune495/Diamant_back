<html>
    <head>
        <title>
            Produit
        </title>
        <style>

            .table {
                width: 100%;
                margin-bottom: 1rem;
                background-color: transparent;
            }

            .table th,
            .table td {
                padding: 0.55rem;
                vertical-align: top;
                border-top: 1px solid #e9ecef;
            }

            .table thead th {
                background-color: black;
                vertical-align: bottom;
                border-bottom: 2px solid #e9ecef;
                color: #d7d9f2;
            }

            .table tbody + tbody {
                border-top: 2px solid #e9ecef;
            }

            .table .table {
                background-color: #fff;
            }

            .table-sm th,
            .table-sm td {
                padding: 0.3rem;
            }

            .table-bordered {
                border: 1px solid #e9ecef;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid #e9ecef;
            }

            .table-bordered thead th,
            .table-bordered thead td {
                border-bottom-width: 2px;
            }

            .table-borderless th,
            .table-borderless td,
            .table-borderless thead th,
            .table-borderless tbody + tbody {
                border: 0;
            }

            .table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(0, 0, 0, 0.03);
            }

            td,
            th {
                border: 1px solid rgb(190, 190, 190);
                padding: 10px;
            }

            td {
                text-align: center;
            }

            tr:nth-child(even) {

            }

            th[scope="col"] {
                background-color: #696969;
                color: #fff;
            }

            th[scope="row"] {
                background-color: #d7d9f2;
            }

            table {
                border-collapse: collapse;
                border: 1px solid rgb(200, 200, 200);
                letter-spacing: 1px;
                font-family: sans-serif;
                font-size: .8rem;
            }
        </style>
    </head>
    <body>
        @if(!isset($is_excel))
            <div>
                <div style="float: left">
                    <img src="{{asset('css/app-assets/images/logo/logoimaaramin.jpg')}}" width="20%" height="10%"> <br>
                </div>
                <div style="float:right">
                    <br>
                   
                </div>
            </div>
            <br><br><br><br>
        @endif
        <div style="text-align:center;text-transform: uppercase;"><strong>Liste des produits</strong></div>
        <br><br>
        <div style="text-align:center">Date: {{date('d-m-Y')}}</div>
        <br><br>
        <table class="table table-bordered w-100" align="center">
            <tr style="font-size: 1.2em;">
                <th><strong>Code</strong></th>
                <th><strong>Designation</strong></th>
                <th><strong>Stock</strong></th>
                <th><strong>Prix Achat</strong></th>
                <th><strong>Prix Vente</strong></th>
                <th><strong>Category</strong></th>
            </tr>
            <tbody>
                {{-- @foreach($taille as $key =>$item)
                    <tr align="center">
                        <td style="">{{$item->nom}}</td>
                        <td style="">{{$item->famille_produit["designation"]}}</td>
                        <td style="">{{$item->description}}</td>
                    </tr>
                @endforeach --}}
                @for ($i = 0; $i < count($data); $i++)
                <tr align="center">
                        <td style="">{{$data[$i]["code"] }}</td>
                        <td style="">{{$data[$i]["designation"] }}</td>
                        <td style="">{{$data[$i]["qte"] }}</td>
                        <td style="">{{$data[$i]["pa"] }}</td>
                        <td style="">{{$data[$i]["pv"] }}</td>
                        <td style="">
                            @if(isset($data[$i]["famille"]))
                                {{ $data[$i]["famille"]["nom"] }}
                            @endif
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </body>
</html>
