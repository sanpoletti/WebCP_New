<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Canasta Familiar</title>
<link href="https://fonts.googleapis.com/css?family=Arvo&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="sigma_grid2.4/grid/gt_grid.css">  
<script type="text/javascript" src="sigma_grid2.4/grid/gt_msg_en.js"></script>
<script type="text/javascript" src="sigma_grid2.4/grid/gt_const.js"></script>
<script type="text/javascript" src="sigma_grid2.4/grid/gt_grid_all.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    body {
        font-family: 'Arvo', sans-serif;
        background: rgba(0,0,0,0.5);
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    /* Fondo oscuro modal */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 999;
    }
    /* Caja del modal */
    .modal-content {
        background: #fff;
        width: 90%;
        max-width: 750px;
        border-radius: 12px;
        padding: 20px 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        animation: fadeIn 0.3s ease-in-out;
        max-height: 90vh;
        overflow-y: auto;
    }
    /* Animación suave */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    /* Botón cerrar */
    .close-btn {
        float: right;
        background: #ff4d4d;
        border: none;
        color: white;
        font-size: 16px;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .close-btn:hover { background: #cc0000; }

    h2.title {
        text-align: center;
        margin-bottom: 10px;
        color: #444;
    }
    #grid1_container {
        margin: 15px 0;
        border: 1px solid #ccc;
    }
    #total {
        font-weight: bold;
        margin: 10px 0;
        text-align: center;
        font-size: 18px;
    }
    table#niveles {
        width: 100%;
        margin-top: 15px;
        border-collapse: collapse;
    }
    table#niveles th, table#niveles td {
        padding: 6px;
        text-align: center;
    }
    table#niveles th {
        color: #555;
    }
    table#niveles td span {
        font-weight: bold;
        color: #222;
    }
</style>
</head>

<body>
    <!-- Modal Overlay -->
    <div class="modal-overlay" id="canastaModal">
        <div class="modal-content">
            
            <h2 class="title">Cálculo de Canasta - Junio 2025</h2>
            <p style="text-align:center; color:#666; margin-bottom:10px;">Gobierno de la Ciudad de Buenos Aires</p>
            <p>Ingrese todos los integrantes del hogar para calcular el valor de la canasta familiar.</p>

            <div id="grid1_container"></div>

            <div id="total">
                Total Coeficiente: <span id="coefTotal">0</span>
            </div>

            <table id="niveles">
                <tr>
                    <th>NLI:</th><td>$ <span id="nli">0</span></td>
                    <th>NLP25:</th><td>$ <span id="nlp25">0</span></td>
                    <th>NLi25:</th><td>$ <span id="nli25">0</span></td>
                </tr>
                <tr>
                    <th>NLP:</th><td>$ <span id="nlp">0</span></td>
                    <th>NLP50:</th><td>$ <span id="nlp50">0</span></td>
                </tr>
                <tr>
                    <th>NLP100:</th><td>$ <span id="nlp100">0</span></td>
                    <th>NLP75:</th><td>$ <span id="nlp75">0</span></td>
                </tr>
            </table>
        </div>
    </div>

<script>
    // Botón cerrar modal
    $("#cerrarModal").click(function(){
        $("#canastaModal").fadeOut(200);
    });

    // --- Lógica de Sigma Grid y cálculos ---
    var data = [
        [null,null,null],[null,null,null],[null,null,null],[null,null,null],
        [null,null,null],[null,null,null],[null,null,null],[null,null,null],
        [null,null,null],[null,null,null],[null,null,null],[null,null,null],
        [null,null,null],[null,null,null],[null,null,null],[null,null,null],
        [null,null,null],[null,null,null]
    ];

    var dsOption= {
        fields :[
            {name : 'edad', type:'string'},
            {name : 'sexo',  type:'string'},
            {name : 'coeficiente', type: 'float'}
        ], 
        recordType : 'object',
        data: data
    };

var colsOption = [
    {id: 'edad' , header: "Edad" , width: 120, mode:"number", format:"#", editable: true,
        editor:{type: "text", validRule:['R','N']} 
    },
    {id: 'sexo' , header: "Sexo" , width: 180, editor: 
        { type: "select", options: {'':null,'Femenino':'Femenino', 'Masculino':'Masculino'} }  
    },
    {id: 'coeficiente', header: "Coeficiente" , width: 150, mode:"number", format:"#.0"} 
];

   var gridOption = {
    id : "grid",
    width: "100%",   // 🔥 Se expande a todo el ancho del modal
    height: "500",   // 🔥 Un poco más alta para mejor visualización
    container : "grid1_container",
    replaceContainer : true,
    dataset : dsOption,
    columns : colsOption,
    toolbarPosition : 'bottom',
    autoLoad : true,
    lightOverRow: true,
    toolbarContent : ''
};

    var grid = new Sigma.Grid(gridOption);

    var lastRowIdx = data.length - 1;
    var lastColIdx = grid.columns.length-1;
    var lastCol = grid.columns[lastColIdx].id;

    grid.afterEdit = function(value, oldValue, record, col, grid) {
        var rowIdx = grid.selectedRows[0].rowIndex;
        if (value == "" || value == null) { return; }
        record[col.getColumnIndex()] = value;
        if (record[0] == null || record[0] == "" || record[1] == null || record[1] == "") { return; }
        if (col.getColumnIndex() != lastColIdx) {
            function recordObject() { this.value = record[lastColIdx]; }
            var ro = new recordObject();
            getCoef(record[0], record[1], grid, rowIdx, lastCol, ro);
        }
    };

    Sigma.Utils.onLoad(function(){ grid.render(); });

    function getCoef(edad, sexo, grid, row, col, recordObject) {
        if (edad=="undefined" || sexo=="undefined" || edad==null || sexo==null) { return; }
        $.ajax({
            url: "getCoef.php?edad="+edad+"&sexo="+sexo,
            context: document.body
        }).done(function ( data ) {
            var coef = data;
            try {
                grid.getCell(row, col).innerHTML = coef;
                recordObject.value = coef;
                recalcSums(col);
            } catch(err) { alert(err); }
        });
    }

    function recalcSums(col) {
        var sum = 0;
        for (var i=0; i < lastRowIdx; i++) {
            var coef = parseFloat(grid.getCell(i, col).innerHTML); 
            if (!isNaN(coef)) { sum += parseFloat(coef); }
        }
        $("#coefTotal").html(formatFloat(sum));
        $("#nli").html( formatFloat(sum * 163756.66) );
        var nlp = sum * 365177.35; 
        var nli = sum * 163756.66;
        $("#nlp").html( formatFloat(nlp) );
        $("#nlp25").html( formatFloat(nlp * 1.25) );
        $("#nlp100").html( formatFloat(nlp * 2.0) );
        $("#nlp50").html( formatFloat(nlp * 1.50) );
        $("#nlp75").html( formatFloat(nlp * 1.75) );
        $("#nli25").html( formatFloat(nli * 1.25) );
    }

    function formatFloat(num) { return Math.round (num*100) / 100; }
</script>
</body>
</html>

