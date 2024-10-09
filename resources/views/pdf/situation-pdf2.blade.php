@extends('pdf.layouts.lay')
@section('content')
<table class="table-outer">
    <!-- ... your table content ... -->
</table>

<center><h4 class="situation-heading">Situation Generale du {{$derniere_date_fermeture}} au {{$current_date}}</h4></center>

<div class="static">
    <table class="table table-bordered">
        <tr>
            <th class="table-header"><p class="badge">DESIGNATION</p></th>
            <th class="table-header"><p class="badge">MONTANT</p></th>
        </tr>
        <tbody>
             {{$montant_total = 0}}
        @foreach($data as $sum)
            {{$montant_total = $montant_total + $sum->total_prix }}
            <tr>
                <td style="font-size:12px;padding: 6px;line-height:15px"><center> {{ \App\Models\Outil::premereLettreMajuscule($sum->designation)}}</center></td>
                    <td style="font-size:12px;padding: 6px"> <center>{{$sum->total_prix}}</center></td>
            </tr>
        @endforeach

        <!--total-->
        <tr>
        <div>
                <p>Total HT</p>
                <p>{{ \App\Models\Outil::formatPrixToMonetaire($montant_total, false, false)}}</p>
            </div>
            </td>
        </tr>
        <tr>
            <td colspan="2"  style="padding-top : 10px;font-size: 11px">
                <p >Arretée à la somme de :</p>  
                <p style="font-weight: bold;font-size: 11px">{{$montant_total !=0 ? $montant_total : $montant_total}}</p> 
            </td>
        </tr>
        </tbody>
    </table>
</div>
@endsection