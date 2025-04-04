

<?php include 'user_navbar.php'; ?>
    <div class="container mt-4">
        <ul class="nav nav-tabs" id="residentTabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#resident-request">Requests (Resident)</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#resident-list">Approved Residents</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#resident-rejected">Rejected Residents</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#vaccination-list">Vaccination List</a>
            </li>
            <li class="nav-item ms-auto">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addResidentModal">+ Add New Resident</button>
            </li>
        </ul>
        <div class="tab-content mt-3">
            <!-- Resident Requests -->
            <div id="resident-request" class="tab-pane fade show active">
                <div class="input-group mb-3">
                    <input type="text" id="searchInputListRequest" class="form-control" placeholder="Search..." oninput="searchResidents('residentRequestTableList','searchInputListRequest')">
                    <button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
                </div>
                <?php include 'resident_request.php'; ?>
            </div>

            <!-- Approved Residents -->
            <div id="resident-list" class="tab-pane fade">
                <div class="input-group mb-3">
                    <input type="text" id="searchInputList" class="form-control" placeholder="Search..." oninput="searchResidents('residentTableList','searchInputList')">
                    <button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
                </div>
                <?php include 'resident_list.php'; ?>
            </div>

            <!-- Rejected Residents -->
            <div id="resident-rejected" class="tab-pane fade">
                <div class="input-group mb-3">
                    <input type="text" id="searchInputRejected" class="form-control" placeholder="Search..." oninput="searchResidents('residentTableRejected','searchInputRejected')">
                    <button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
                </div>
                <?php include 'resident_rejected.php'; ?>
            </div>

            <!-- Vaccination List -->
            <div id="vaccination-list" class="tab-pane fade">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="vaccinationFilter" class="form-label">Filter by Vaccination Status:</label>
                        <select id="vaccinationFilter" class="form-select">
                            <option value="">All</option>
                            <option value="Yes">Vaccinated</option>
                            <option value="No">Not Vaccinated</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="searchInputVaccination" class="form-label">Search:</label>
                        <input type="text" id="searchInputVaccination" class="form-control" placeholder="Enter search keyword">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="vaccinationTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Account ID</th>
                                <th>National ID</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Birthdate</th>
                                <th>Vaccination Status</th>
                                <th>Vaccine Name</th>
                                <th>Vaccine Type</th>
                                <th>Vaccination Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include 'vaccinated_individuals.php'; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
  

        <?php include 'resident_modal.php'; ?>
        <script src="../js/script.js"></script>
        <script src="vaccination_search.js"></script>
        <script src="../js/resident.js" defer></script>
    </body>
</html>
