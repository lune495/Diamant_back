@extends('pdf.layouts.layout-export2')
@section('title', "Situation Generale")
@section('content')

<h4 class="situation-heading">Historique Generale {{$nom_module}} du {{$derniere_date_fermeture}} au {{$current_date}}</h4>
<div class="table-container">
    <!-- Tableau de gauche (RECETTE) -->
    <div class="table-wrapper left">
        <table class="custom-table">
            <!-- En-tête -->
            <tr>
                <th>Date</th>
                <th>Nom Patient</th>
                <th>Service</th>
                <th>Medecin</th>
            </tr>
            <!-- Contenu -->
            {{$montant_total = 0}}
            {{$montant_total_service = 0}}
            @php
                $groupedData = $data->groupBy(function($item) {
                    return $item->medecin->nom;
                });
            @endphp
            @foreach($groupedData as $medecin => $rows)
                @php
                    $rowspan = count($rows);
                    $firstRow = true;
                @endphp
                @foreach($rows as $sum)
                    @foreach($sum->element_services as $element_service)
                        {{$montant_total_service = $element_service->type_service->prix}}
                    @endforeach
                    {{$montant_total = $montant_total + $montant_total_service}}
                    <tr>
                        <td><center>{{ $sum->created_at }}</center></td>
                        <td>{{\App\Models\Outil::toUpperCase($sum->nom_complet)}}</td>
                        <td>{{\App\Models\Outil::toUpperCase($sum->module->nom)}}</td>
                        @if($firstRow)
                            <td rowspan="{{ $rowspan }}">{{ \App\Models\Outil::toUpperCase($medecin) }}</td>
                            @php $firstRow = false; @endphp
                        @endif
                    </tr>
                @endforeach
            @endforeach
            <tr>
                <td colspan="5">
                    <div>
                        <p class="badge" style="line-height:15px;">Total</p>
                        <p style="line-height:5px;text-align:center"><center>{{ \App\Models\Outil::formatPrixToMonetaire($montant_total, false, true)}}</center></p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<!-- Pied de page -->
<div class="footer">
    <div class="signatures">
        <div class="signature-section left">
            <p>Signature du Principal </p>
            <!-- Ajoutez ici un espace ou une zone pour la signature du principal -->
        </div>
        <div class="signature-section right">
            <p>Signature du Caissier </p>
            <!-- Ajoutez ici un espace ou une zone pour la signature du caissier -->
        </div>
    </div>
</div>

<style>
    .footer {
        text-align: center;
    }

    .signatures {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 10px;
        flex-wrap: nowrap;
    }

    .signature-section {
        border-top: 1px solid #ccc;
        padding-top: 10px;
    }

    .left {
        text-align: left;
        flex: 1;
    }

    .right {
        text-align: right;
        flex: 1;
    }
</style>

@endsection