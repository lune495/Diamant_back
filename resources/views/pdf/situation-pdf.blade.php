@extends('pdf.layouts.layout-export2')
@section('title', "Situation Generale")
@section('content')

<h4 class="situation-heading">Situation Generale du {{$derniere_date_fermeture}} au {{$current_date}}</h4>
<div class="table-container">
    <!-- Tableau de gauche (RECETTE) -->
    <div class="table-wrapper left">
        <h4>RECETTE</h4>
        <table class="custom-table">
            <!-- En-tête -->
            <tr>
                <th>DESIGNATION</th>
                <th>MONTANT</th>
            </tr>
            <!-- Contenu -->
            <!-- ... Votre boucle foreach existante ... -->
            {{$montant_total = 0}}
            @foreach($data as $sum)
                {{$montant_total = $montant_total + $sum->total_prix }}
                <tr>
                    <td><center> {{ \App\Models\Outil::toUpperCase($sum->designation)}}</center></td>
                    <td>{{\App\Models\Outil::formatPrixToMonetaire($sum->total_prix, false, false)}}</td>
                </tr>
            @endforeach
            <tr>
                <td>PHARMACIE</td><td>{{$pharmacie ? \App\Models\Outil::formatPrixToMonetaire($pharmacie, false, false) : 0}}</td>
            </tr>
            <tr>
                <td colspan="2">
                    <div>
                        <p class="badge" style="line-height:15px;">Total</p>
                        <p style="line-height:5px;text-align:center">{{ \App\Models\Outil::formatPrixToMonetaire($montant_total + ($pharmacie ? $pharmacie : 0), false, false)}}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tableau de droite (DEPENSE) -->
    <div class="table-wrapper left">
        <h4>DEPENSE</h4>
        <table class="custom-table">
            <!-- En-tête -->
            <tr>
                <th>Nature</th>
                <th>MONTANT</th>
            </tr>
            <!-- Contenu -->
            <!-- ... Votre boucle foreach existante pour les dépenses ... -->
            {{$montant_total_depense = 0}}
            @foreach($depenses as $dep)
                {{$montant_total_depense = $montant_total_depense + $dep->montant }}
                <tr>
                    <td><center> {{ \App\Models\Outil::premereLettreMajuscule($dep->nom)}}</center></td>
                    <td> <center>{{$dep->montant}}</center></td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2">
                    <div>
                        <p class="badge" style="line-height:15px;">Total</p>
                        <p style="line-height:5px;text-align:center">{{ \App\Models\Outil::formatPrixToMonetaire($montant_total_depense, false, false)}}</p>
                    </div>
                </td>
            </tr>
            <!-- Ajoutez la ligne colorée si nécessaire -->
                <tr class="colorful-row">
                    <td colspan="2" style="padding-top: 10px; font-size: 15px">
                        <p>Solde Caisse :</p>
                        <p style="font-weight: bold; font-size: 20px">{{ \App\Models\Outil::formatPrixToMonetaire($montant_total - $montant_total_depense + ($pharmacie ? $pharmacie : 0), false, true)}}</p>
                    </td>
                </tr>
        </table>
        <!-- ... Votre code existant ... -->

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
    /* Ajoutez ce style à votre section de style existante ou à votre fichier de style externe */

    .footer {
        /* margin-top: 20px;
        padding-top: 20px; */
        text-align: center;
    }

    .signatures {
        display: flex;
        align-items: center; /* Centre les éléments verticalement */
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
        flex: 1; /* Pour permettre à la signature du Principal de pousser la signature du Caissier à droite */
    }

    .right {
        text-align: right;
        flex: 1; /* Pour permettre à la signature du Caissier de pousser la signature du Principal à gauche */
    }

    /* Ajoutez des styles de signature spécifiques ici si nécessaire */
</style>

    </div>
</div>

<!-- ... Le reste de votre modèle ... -->

@endsection
