<?php
include '../templates/header.html';
include '../templates/conn.php';
session_start();
session_unset();
?>
<div class="container">
    <div class="pageTitle"> 
        <h4>Dodaj paszę:</h4>
    </div>
    <form action="addFodder.php" method="post">
        <div class="input-group input-group-sm mb-1">
            <label for="nazwa" class="form-control tags nameLabel">Nazwa paszy</label>
            <input type="text" class="form-control" maxlength="50" name="nazwa_paszy" required>
        </div>
        <div class="input-group input-group-sm mb-1">
            <label for="oznaczenie" class="form-control tags nameLabel">Oznaczenie</label>
            <input type="text" class="form-control" maxlength="20" name="oznaczenie" required>
        </div>
        <div class="input-group input-group-sm mb-1">
            <label for="ilosc" class="form-control tags nameLabel">Ilość [kg]</label>
            <input type="number" class="form-control" min="1" max="999999" step="0.01" name="ilosc" required>
        </div>
        <input type="text" class="form-control" maxlength="50" name="id_zwierze" id="id_zwierze" hidden>
        
    <div class="pageTitle"> 
        <h5>Wybierz zwierzę:</h5>
    </div>
    <div class="boxRecipe">
        <?php
            $sql = "SELECT * FROM zwierzęta";
            $result = $conn->query($sql);
            $animalGroups = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $animalGroups[$row["grupa_zwierzat"]][$row["grupa_produkcyjna"]][$row["faza_produkcji"]][] = $row;
                }
            }
            foreach ($animalGroups as $animalGroup => $productionGroups) {
                echo "<div class='group' id='$animalGroup' data-bs-toggle='tooltip' data-bs-title='Kliknij aby rozwinąć/zwinąć' onclick='showHideR(\"$animalGroup" . "Details\")'><span class='form-control form-control-sm groupColor'>$animalGroup</span></div>";
                echo "<div id='$animalGroup" . "Details' style='display: none;'>";

                foreach ($productionGroups as $productionGroup => $phases) {
                    echo "<div class='input-group inputSection group' data-bs-toggle='tooltip' data-bs-title='Kliknij aby rozwinąć/zwinąć' id='productionGroup" . $productionGroup . "' onclick='showHideR(\"productionGroup" . $productionGroup . "Details\")'>";
                    echo "<label class='form-control form-control-sm nameWrap'>" . $productionGroup . "</label>";
                    echo "</div>";
                    echo "<div id='productionGroup" . $productionGroup . "Details' style='display: none;'>";

                    foreach ($phases as $phase => $recipes) {
                        echo "<div class='input-group inputSection2 group' data-bs-toggle='tooltip' data-bs-title='Kliknij aby rozwinąć/zwinąć' id='phase" . $phase . "' onclick='showHideR(\"phase" . $phase . "Details" . $productionGroup . "\")'>";
                        echo "<label class='form-control form-control-sm nameWrap'>" . $phase . "</label>";
                        echo "</div>";
                        echo "<div id='phase" . $phase . "Details" . $productionGroup . "' style='display: none;'>";
                        foreach ($recipes as $recipe) {
                            echo "<div class='input-group inputSection3' id='recipe" . $recipe["id"] . "'>";
                            echo "<label class='form-control form-control-sm nameWrap'>" . $recipe["nazwa"] . "</label>";
                            echo "<button class='btn btn-sm btn-outline-secondary' type='button' onclick='select(\"" . $recipe["id"] . "\",\"" . $recipe["nazwa"] . "\", \"" . $recipe["grupa_zwierzat"] . "\", \"" . $recipe["grupa_produkcyjna"] . "\", \"" . $recipe["faza_produkcji"] . "\")'>Wybierz</button>";
                            echo "</div>";
                        }
                        echo "</div>";
                    }
                    echo "</div>";
                }
                echo "</div>";
            }
        ?>
    </div>
    <div class="pageTitle"> 
        <h5> Wybrane zwierzę: </h5>
        <h5 id="selectAnimal" hidden> Nie wybrano zwierzęcia </h5>
    </div>
        <div class='specification-container'>
            <div class='specification'>
                <div class='input-group input-group-sm'>
                    <label class='form-control tags'>
                    Nazwa
                    </label>
                    <label class='form-control' id='name'>
                    </label>
                </div>

                <div class='input-group input-group-sm'>
                    <label class='form-control tags'>
                    Grupa zwierząt
                    </label>
                    <label class='form-control' id='animalG'>
                    </label>
                </div>
            </div>

            <div class='specification'>
            <div class='input-group input-group-sm'>
                    <label class='form-control tags'>
                    Grupa produkcyjna
                    </label>
                    <label class='form-control' id='productionG'>
                    </label>
                </div>

                <div class='input-group input-group-sm'>
                    <label class='form-control tags'>
                    Faza produkcji
                    </label>
                    <label class='form-control' id='productionP'>
                    </label>
                </div>
            </div>
        </div>
        <div class="buttonSubmit">
            <button type="submit" name="addFodder" class="btn btn-primary mb-1">Dalej</button>
        </div>
    </form>
</div>
<?php
if(isset($_POST['addFodder']))
{    
    $id_zwierze = $_POST['id_zwierze'];
    $nazwa_paszy = $_POST['nazwa_paszy'];
    $oznaczenie = $_POST['oznaczenie'];
    $ilosc = $_POST['ilosc'];

    $check = mysqli_query($conn, "SELECT COUNT(*) FROM pasze WHERE nazwa_paszy = '$nazwa_paszy'");
    $count = mysqli_fetch_array($check)[0];

    if ($count > 0) {
        echo "<script>
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault();
            alert('Nazwa paszy już istnieje w bazie danych.');
        });
        </script>";
    } else {
        $sql = "INSERT INTO pasze (id_zwierze,nazwa_paszy,oznaczenie,ilosc)
        VALUES ('$id_zwierze','$nazwa_paszy','$oznaczenie','$ilosc')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('New record has been added successfully !');</script>";
        } else {
            echo "Error: " . $sql . ":-" . mysqli_error($conn);
        }
        mysqli_close($conn);

        $_SESSION["nazwa_paszy"] = $nazwa_paszy;
        $_SESSION["oznaczenie"] = $oznaczenie;
        $_SESSION["ilosc"] = $ilosc;

        header("Location: addFodderN.php?id=$id_zwierze");

    }
}
?>

<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    function showHideR(id) {
    var x = document.getElementById(id);
    var group = document.getElementById(id.replace("Details", ""));
        if (x.style.display === "none") {
            x.style.display = "block";
            group.classList.add("open");
        } else {
            x.style.display = "none";
            group.classList.remove("open");
        }
    }

    var id_zwierze = null;
    function select(id, nazwa, grupa_zwierzat, grupa_produkcyjna, faza_produkcji){
        id_zwierze = id;

        console.log(id);
        var id_zwierze = document.getElementById("id_zwierze");
        id_zwierze.value = id;
        
        var name = document.getElementById("name");
        name.innerHTML = nazwa;

        var animalG = document.getElementById("animalG");
        animalG.innerHTML = grupa_zwierzat;

        var productionG = document.getElementById("productionG");
        productionG.innerHTML = grupa_produkcyjna;

        var productionP = document.getElementById("productionP");
        productionP.innerHTML = faza_produkcji;

        var selectAnimal = document.getElementById("selectAnimal");
        if (id_zwierze.value === "") {
            event.preventDefault();
            selectAnimal.hidden = false;
        }else
        {
            selectAnimal.hidden = true;
        }
    }

    document.querySelector('form').addEventListener('submit', function(event) {
        var id_zwierze = document.getElementById("id_zwierze");
        var selectAnimal = document.getElementById("selectAnimal");
        if (id_zwierze.value === "") {
            event.preventDefault();
            selectAnimal.hidden = false;
        }else
        {
            selectAnimal.hidden = true;
        }
    });
</script>
</body>
</html>						