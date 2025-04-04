<?php
include 'user_navbar.php';

$query = "SELECT * FROM announcement";
$result = $conn->query($query);

$permit = array();
while($row = $result->fetch_assoc()){
    $permit[] = $row; 
}

$query1 = "SELECT * FROM purok ORDER BY `name`";
$result1 = $conn->query($query1);
$purok = array();
while($row = $result1->fetch_assoc()){
    $purok[] = $row; 
}

?>

<div class="container mt-4">
    <h2 class="mb-3">Announcement</h2>
    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['success']; ?> <?= $_SESSION['success']=='danger' ? 'bg-danger text-light' : null ?>" role="alert">
            <?php echo $_SESSION['message']; ?>
        </div>
    <?php unset($_SESSION['message']); ?>
    <?php endif ?>
    <div class="search">
        <div class="input-group mb-3">
            <input type="text" id="myInput" class="form-control" placeholder="Search..." oninput="searchTable()">
            <button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
        </div>
    </div>
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">+ Add New</button>
    </div>
    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table id="residenttable" class="table table-striped table-hover border">
            <thead class='table-dark'>
                <tr>
                    <th scope="col">Purok</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Message</th>
                    <th scope="col">Status</th>
                    <th scope="col">Date Announced</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($permit)): ?>
                    <?php foreach($permit as $row): ?>
                    <tr>
                        <td><?= $row['purok'] ?></td>
                        <td><?= $row['title'] ?></td>
                        <td><?= $row['message'] ?></td>
                        <td><span class="text-<?= ($row['status'] == 'DRAFT')?'danger':'success'?>"><?= $row['status'] ?></span></td>
                        <td><?= ($row['date_announced'] ? date('F d, Y', strtotime($row['date_announced'])):'-')?></td>
                        <td>
                            <div class="form-button-action">
                                <button type="button" class="btn btn-link btn-primary" 
                                    title="Edit Announcement" onclick="editAnnouncement(<?php echo htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>)"
                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <a type="button" data-toggle="tooltip" href="send_announcement.php?id=<?= $row['id'] ?>" class="btn btn-link btn-primary" onclick="return confirm(`Are you sure you want to send this to the resident's email?`);" data-original-title="Send Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                <a type="button" data-toggle="tooltip" href="remove_announcement.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this announcement?');" class="btn btn-link btn-danger" data-original-title="Remove">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No Announcement Available</td>
                        </tr>
                    <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Create Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="save_announcement.php">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Purok</label>
                        <select class="form-control" required name="purok">
                            <option disabled selected>Select Purok Name</option>
                            <option value="ALL">ALL</option>
                            <?php foreach($purok as $row):?>
                                <option value="<?= ucwords($row['name']) ?>"><?= $row['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input class="form-control mb-2" placeholder="Enter subject" name="title" required/>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea class="form-control mb-2" placeholder="Enter message" name="message" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="edit_announcement.php">
                <div class="modal-body">
                    <input type="hidden" name="id" id="announcement_id">
                    <div class="form-group">
                        <label>Purok</label>
                        <select class="form-control" required name="purok" id="edit_purok">
                            <option disabled selected>Select Purok Name</option>
                            <option value="ALL">ALL</option>
                            <?php foreach($purok as $row):?>
                                <option value="<?= ucwords($row['name']) ?>"><?= $row['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input class="form-control mb-2" placeholder="Enter subject" id="edit_title" name="title" required/>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea class="form-control mb-2" placeholder="Enter message" id="edit_message" name="message" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editAnnouncement(data) {
    // Populate the edit form with the announcement data
    document.getElementById('announcement_id').value = data.id;
    document.getElementById('edit_purok').value = data.purok;
    document.getElementById('edit_title').value = data.title;
    document.getElementById('edit_message').value = data.message;
}

function searchTable() {
    const input = document.getElementById("myInput");
    const filter = input.value.toUpperCase();
    const table = document.getElementById("residenttable");
    const tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        const tdPurok = tr[i].getElementsByTagName("td")[0];
        const tdTitle = tr[i].getElementsByTagName("td")[1];
        const tdMessage = tr[i].getElementsByTagName("td")[2];
        
        if (tdPurok || tdTitle || tdMessage) {
            const txtValue = (tdPurok.textContent || tdPurok.innerText) + " " + 
                            (tdTitle.textContent || tdTitle.innerText) + " " + 
                            (tdMessage.textContent || tdMessage.innerText);
            
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>