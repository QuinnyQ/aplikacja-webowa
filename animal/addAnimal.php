<?php
include '../templates/header.html';
include '../templates/conn.php';
session_start();
session_unset();
?>
<div class="container">
    <br> 
    <div class="pageTitle">
        <h4>Dodaj zwierzę:</h4>
    </div>
    
    <form action="addAnimal.php" method="post">
                <div class="input-group mb-3">
                    <label for="nazwa" class="form-control tags nameLabel">Nazwa</label>
                    <input type="text" class="form-control" maxlength="50" name="nazwa" required>
                </div>
                <select id="grupa_zwierzat" name="grupaz" class="form-control mb-3" required>
                    <option selected value="">Wybierz grupę zwierząt</option>
                    <option value="Drób">Drób</option>
                    <option value="Trzoda chlewna">Trzoda chlewna</option>
                </select>
                <select id="grupa_produkcyjna" name="grupap" class="form-control mb-3" required>
                    <option selected value="">Wybierz grupę produkcyjną</option>
                </select>
                <select id="faza_produkcji" name="fazap" class="form-control mb-3" required>
                    <option selected value="">Wybierz fazę produkcji</option>
                </select>
            <div class="buttonSubmit">
                <button type="addAnimal" name="addAnimal" class="btn btn-primary mb-3">Dodaj</button>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var fazy_produkcji = {
        "Drób": {
            "Kurczęta brojlery": ["Starter", "Grower", "Finisher"],
            "Kaczki": ["Starter", "Grower", "Finisher"],
            "Gęsi": ["Starter", "Grower", "Finisher"]
        },
        "Trzoda chlewna": {
            "Prosięta i Warchlaki": ["Prestarter 1", "Prestarter 2", "Starter"],
            "Tuczniki": ["Starter", "Grower", "Finisher I", "Finisher II"],
        }
    };

    $('#grupa_zwierzat').change(function() {
        var grupa_zwierzat = $(this).val();
        var grupy_produkcyjne = fazy_produkcji[grupa_zwierzat];
        $('#grupa_produkcyjna').empty().append('<option selected value="">Wybierz grupę produkcyjną</option>');
        $('#faza_produkcji').empty().append('<option selected value="">Wybierz fazę produkcji</option>');
        $.each(grupy_produkcyjne, function(key, _) {
            $('#grupa_produkcyjna').append('<option value="' + key + '">' + key + '</option>');
        });
    });

    $('#grupa_produkcyjna').change(function() {
        var grupa_zwierzat = $('#grupa_zwierzat').val();
        var grupa_produkcyjna = $(this).val();
        var fazy = fazy_produkcji[grupa_zwierzat][grupa_produkcyjna];
        $('#faza_produkcji').empty().append('<option selected value="">Wybierz fazę produkcji</option>');
        $.each(fazy, function(_, value) {
            $('#faza_produkcji').append('<option value="' + value + '">' + value + '</option>');
        });
    });
});
</script>

<?php
if(isset($_POST['addAnimal']))
{    
    $nazwa= $_POST['nazwa'];
    $grupaz= $_POST['grupaz'];
    $grupap= $_POST['grupap'];
    $fazap= $_POST['fazap'];

    $check = mysqli_query($conn, "SELECT COUNT(*) FROM `zwierzęta` WHERE nazwa = '$nazwa'");
    $count = mysqli_fetch_array($check)[0];

    if ($count > 0) {
        echo "<script>alert('Nazwa zwierzęcia już istnieje w bazie danych.');</script>";
    } else {
        $sql = "INSERT INTO zwierzęta (nazwa,grupa_zwierzat,grupa_produkcyjna,faza_produkcji)
        VALUES ('$nazwa','$grupaz','$grupap','$fazap')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('New record has been added successfully !');</script>";
        } else {
            echo "Error: " . $sql . ":-" . mysqli_error($conn);
        }
        mysqli_close($conn);

        $_SESSION["nazwa"] = $nazwa;
        $_SESSION["grupaz"] = $grupaz;
        $_SESSION["grupap"] = $grupap;
        $_SESSION["fazap"] = $fazap;

        header("Location: addAnimalN.php");
    }
}
?>
</body>
</html>
