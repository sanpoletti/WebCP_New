
<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<h3>OBSERVACIONES SINTYS
<a href="#" id="toggleHistorico">(Ver histórico...)</a></h3>

<div id="historicoObservaciones" style="display:none; margin-top:20px;">
<iframe src="https://example.com"
    style="width:100%; height:200px; border:1px solid #aaa; border-radius:8px;">
    </iframe>
    </div>
    
    <script>
    $(function(){
        $("#toggleHistorico").off('click').on('click', function(e){
            e.preventDefault();
            e.stopPropagation(); // 🔒 Bloquea cualquier otro click listener
            $("#historicoObservaciones").stop(true, true).slideToggle(300);
            $(this).text(
                $(this).text() === '(Ver histórico...)'
                ? '(Ocultar histórico...)'
                : '(Ver histórico...)'
                );
            return false; // 🚫 Previene cualquier propagación
        });
    });
        </script>
        
        </body>
        </html>
        