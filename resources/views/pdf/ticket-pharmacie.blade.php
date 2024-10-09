<!DOCTYPE html>
<html>
    <head>
        <style>
            /**
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 0cm;
            }
            @media print {
                html, body {
                    height: 100%;
                }
            }
            body {
                display: block;
                position: center;
                margin-top: 0cm;
                margin-left: 0.6cm;
                margin-right: 0.7cm;
                margin-bottom: 1cm;
                font-size: 0.6em;
                font: 16pt/1.5 'Raleway','Cambria', sans-serif;
                font-weight: 300;
                background:  #fff;
                color: black;
                -webkit-print-color-adjust:  exact;
                /*border:1px solid black;*/
            }
            section{
                margin-top: -2px;
                margin-bottom: -2px;
            }
            div{
            .droite{
                text-align:right;
                font-size: 0.75em;
                margin-top:-30px;
            }
            .gauche{
                text-align:left;
                font-size: 0.8em;
            }
            hr{
                border-top: 1px dotted red;
            }
            nav{
                /*border:1px solid black;*/
                margin-top:30px;
                float: center;
            }
            table {
                font-family: "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
                margin-bottom: 5px;
            }
            td, th {
                text-align: center;
                font-size: 0.75em;
            }
            header{
                /*margin-left: auto;
                margin-right: auto;*/
            }
        </style>
    <head>
    <body>
        <div>
            <section class="droite" style="text-align: center">
                <header style="margin-top : 50px;font-size: 14px;">
                     Ticket Pharmacie
                </header>
                <br>

                <div style="font-size: 10px;font-weight:bold">
                    <img src="{{asset('app-assets/assets/images/LOGO.PNG')}}" style="width: 80px; margin-top: 10px;"> <br>
                    <!--IMAARA-->
                </div>

                <div style="margin:10px 0">
                            Centre Medico Social
                    CHIFAA No 023 Parcelles Assainies-Unité 24
                </div>
                <div style="margin:10px 0">
                    TEL : +221 33 821 25 12 / TEL : 77 270 72 22
                </div>
                ************************
                <div style="margin:7px 0">
                Client(e) : {{$vente->nom_client}}
                </div>
                ************************

                <dt  style="font-size: 18px;font-weight:bold">Vente N°{{$vente->id}}</dt>
            </section>
            <section  style="margin-top : 30px">
                <table>
                    <tbody>
                        <tr>
                            <td style="padding-left: 15px;">TOTAL</td>
                            <td style="padding-left: 15px;">{{\App\Models\Outil::formatPrixToMonetaire($vente->montant, false, true)}}</td>
                        </tr>
                    </tbody>
                </table>
            </section>
            <div style="overflow: hidden;margin-top : 20px">**************************************</div>
        </div>
    </body>
  <footer>
        <p style="font-size:10px">Votre Santé Notre Priorité </p>
</footer>
</html>
