<?php
include '../templates/header.html';
include '../templates/conn.php';
?>
<div class="container">
<div class="pageTitle">
    <h4>Stworzone zwierzęta:</h4>
</div>
<?php
$grupa_zwierzat = isset($_POST['grupa_zwierzat']) ? $_POST['grupa_zwierzat'] : '';
$grupa_produkcyjna = isset($_POST['grupa_produkcyjna']) ? $_POST['grupa_produkcyjna'] : '';
$faza_produkcji = isset($_POST['faza_produkcji']) ? $_POST['faza_produkcji'] : '';

if(isset($_POST['deleteFilters'])){
    $grupa_zwierzat = '';
    $grupa_produkcyjna = '';
    $faza_produkcji = '';
}
?>

<form method="post" class="row g-3">
    <div class="col-auto">
        <label for="grupa_zwierzat" class="visually-hidden">Grupa zwierząt</label>
        <select name="grupa_zwierzat" class="form-select">
            <option value="">Wybierz grupę zwierząt</option>
            <option value="Drób" <?php if ($grupa_zwierzat == 'Drób') echo 'selected'; ?> >Drób</option>
            <option value="Trzoda chlewna" <?php if ($grupa_zwierzat == 'Trzoda chlewna') echo 'selected'; ?> >Trzoda chlewna</option>
        </select>
    </div>
    <div class="col-auto">
        <label for="grupa_produkcyjna" class="visually-hidden">Grupa produkcyjna</label>
        <select name="grupa_produkcyjna" class="form-select">
            <option value="">Wybierz grupę produkcyjną</option>
            <option value="Kurczęta brojlery" <?php if ($grupa_produkcyjna == 'Kurczęta brojlery') echo 'selected'; ?>>Kurczęta brojlery</option>
            <option value="Kaczki" <?php if ($grupa_produkcyjna == 'Kaczki') echo 'selected'; ?>>Kaczki</option>
            <option value="Gęsi" <?php if ($grupa_produkcyjna == 'Gęsi') echo 'selected'; ?>>Gęsi</option>
            <option value="Prosięta i Warchlaki" <?php if ($grupa_produkcyjna == 'Prosięta i Warchlaki') echo 'selected'; ?>>Prosięta i Warchlaki</option>
            <option value="Tuczniki" <?php if ($grupa_produkcyjna == 'Tuczniki') echo 'selected'; ?>>Tuczniki</option>
        </select>
    </div>
    <div class="col-auto">
        <label for="faza_produkcji" class="visually-hidden">Faza produkcji</label>
        <select name="faza_produkcji" class="form-select">
            <option value="">Wybierz fazę produkcji</option>
            <option value="Starter" <?php if ($faza_produkcji == 'Starter') echo 'selected'; ?>>Starter</option>
            <option value="Grower" <?php if ($faza_produkcji == 'Grower') echo 'selected'; ?>>Grower</option>
            <option value="Finisher" <?php if ($faza_produkcji == 'Finisher') echo 'selected'; ?>>Finisher</option>
            <option value="Finisher I" <?php if ($faza_produkcji == 'Finisher I') echo 'selected'; ?>>Finisher I</option>
            <option value="Finisher II" <?php if ($faza_produkcji == 'Finisher II') echo 'selected'; ?>>Finisher II</option>
            <option value="Prestarter 1" <?php if ($faza_produkcji == 'Prestarter 1') echo 'selected'; ?>>Prestarter 1</option>
            <option value="Prestarter 2" <?php if ($faza_produkcji == 'Prestarter 2') echo 'selected'; ?>>Prestarter 2</option>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" name="filter" class="btn btn-primary mb-3">Filtruj</button>
        <button type="submit" name="deleteFilters" class="btn btn-primary mb-3" onclick="clear()">Wyczyść filtry</button>
    </div>
</form>
    

<?php
$sql = "SELECT id, nazwa, grupa_zwierzat, grupa_produkcyjna, faza_produkcji FROM zwierzęta";
$conditions = [];
if ($grupa_zwierzat != '') {
    $conditions[] = "grupa_zwierzat = '$grupa_zwierzat'";
}
if ($grupa_produkcyjna != '') {
    $conditions[] = "grupa_produkcyjna = '$grupa_produkcyjna'";
}
if ($faza_produkcji != '') {
    $conditions[] = "faza_produkcji = '$faza_produkcji'";
}
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}
$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo '<div class="row g-4">';
        while ($row = $result->fetch_assoc()) {
            echo '<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-12 mb-3">';
            echo  '<div class="card h-100">';
            echo    '<div class="card-body">';
            echo      '<h5 class="card-title">';
            echo        'Nazwa: ' . $row["nazwa"];
            echo      '</h5>';
            echo      '<p class="card-text">';
            echo        'Grupa zwierząt: ' . $row["grupa_zwierzat"];
            echo      '</p>';
            echo      '<p class="card-text">';
            echo        'Grupa produkcyjna: ' . $row["grupa_produkcyjna"];
            echo      '</p>';
            echo      '<p class="card-text">';
            echo        'Faza produkcji: ' . $row["faza_produkcji"];
            echo      '</p>';
            echo      '<a href="viewAnimal.php?id=' . $row["id"] . '" class="btn btn-primary">Zobacz więcej</a>';
            echo    '</div>';
            echo  '</div>';
            echo '</div>';
        }
    } else {
        echo "Brak wyników";
    }
    echo '</div>';

    $conn->close();

?>
</div>
<script>
    function clearFilters() {
        document.querySelector('select[name="grupa_zwierzat"]').value = ''; 
        document.querySelector('select[name="grupa_produkcyjna"]').value = ''; 
        document.querySelector('select[name="faza_produkcji"]').value = ''; 
        document.querySelector('form').reset();
    }
</script>
</body>
</html>