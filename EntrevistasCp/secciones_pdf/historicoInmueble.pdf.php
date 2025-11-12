<style>
.bg-beige {
    background-color: #f5f5dc;
}
</style>

<?php
if (count($hdInmueble->getData()) > 0) {
?>
    
    <table style="width: 100%; border: solid 1px black; border-collapse: collapse;" align="center">
        <tr>
            <th style="width: 80%; text-align: left; border: solid 1px black;"><i>Detalle</i></th>
            <th style="width: 4%; text-align: left; border: solid 1px black;"><i>Año</i></th>
        </tr>
        <?php
        foreach ($hdInmueble->getData() as $DOC) {
            echo '<tr class="bg-beige">';
            echo '<td style="text-align: left; border: solid 1px black;">' . $DOC->getProperty('descripcion') . '</td>';
            echo '<td style="text-align: left; border: solid 1px black;">' . $DOC->getProperty('anio') . '</td>';
            echo '</tr>';
        }
        ?>
    </table>
<?php
}
?>