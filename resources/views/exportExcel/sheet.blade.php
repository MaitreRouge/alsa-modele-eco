@php use App\Models\Categorie;use App\Models\Prestation; @endphp

<table>
    <thead>
    <tr>
        <th style="background-color: #c0bfbf">Fournisseur</th>
        <th style="background-color: #c0bfbf">Prestation</th>
        <th style="background-color: #c0bfbf">Qte</th>
        <th style="background-color: #c0bfbf">PrixAchatMensuel</th>
        <th style="background-color: #c0bfbf">PrixAchatFAS</th>
        <th style="background-color: #c0bfbf">PdvMensuel</th>
        <th style="background-color: #c0bfbf">PdvFAS</th>
    </tr>
    </thead>
    <tbody>
    @foreach($prestations as $line)
            <?php
            $p = (Prestation::where("id", $line->catalogueID)
                ->where("version", $line->version)
                ->get())[0];
            ?>
        <tr>
            <td>{{ $p->findSupplier()->label??"Alsatis" }}</td>
            <td>{{ $line->customName??$p->label }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $line->quantite }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $p->prixFAS }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $p->prixMensuel }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ ($line->pdvFAS??$p->prixFAS) }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ ($line->pdvMensuel??$p->prixMensuel) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
