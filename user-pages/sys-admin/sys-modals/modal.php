<div class="modal modal-blur fade" id="modal-add-user" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addUserForm">
        <div class="modal-body">
          <!-- Name Field -->
            <div class="mb-3">
              <label class="form-label">Name</label>
              <select class="form-select" name="name" id="staffSelect" required>
                <option value="">-- Select Staff Name --</option>
                <?php
                $staffList = $system->getStaff($db); // Use your SYSTEM instance
                foreach ($staffList as $staff):
                  $fullName = $staff['firstname'] . ' ' . $staff['midname'] . ' ' . $staff['surname'];
                  $uaid = $staff['staff_id'];
                  if (!empty($staff['extension'])) {
                      $fullName .= ', ' . $staff['extension'];
                  }
                ?>
                  <option value="<?php echo htmlspecialchars($uaid); ?>"
                          data-dept="<?php echo htmlspecialchars($staff['dept_id']); ?>">
                    <?php echo htmlspecialchars($fullName); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <input type="hidden" name="dept_id" id="dept_id">

          <!-- Username Field -->
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Enter username" required>
          </div>

          <!-- Password Field -->
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Enter password" required>
          </div>

          <!-- Status Field -->
          <div class="mb-3" style="display: none;">
          <label class="form-label">Status</label>
          <input type="hidden" name="status" value="1" required>
        </div>

          <!-- Position Field -->
          <div class="mb-3">
            <label class="form-label">Position</label>
            <select class="form-select" name="position" id="position" required>
              <option value="Requestor">Requestor</option>
              <option value="Dept. Head">Department Head</option>
              <option value="Admin">Admin</option>
              <option value="Purchaser">Purchaser</option>
              <option value="PR Custodian">PR Custodian</option>
              <option value="Stockroom">Stockroom</option>
            </select>
          </div>


          <!-- Hidden modid Input -->
          <input type="hidden" name="modid" value="2" id="modid">

        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
          <button type="submit" class="btn btn-primary ms-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M12 5l0 14" />
              <path d="M5 12l14 0" />
            </svg>
            Add User
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

 <!-- User Update Modal -->
<div class="modal modal-blur fade" id="modal-update-user" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="updateUserForm">
        <div class="modal-body">
          <!-- User ID (Hidden) -->
          <input type="hidden" name="uaid" id="update-uaid">

          <!-- Name Field -->
          <!-- Staff Name Dropdown -->
          <!-- Hidden field that will be submitted -->
          <input type="hidden" name="name" id="update-name" required>

          <!-- Optional: Hidden select for internal use (not submitted) -->
          <select class="form-select d-none" id="staff-selector">
              <option value="">-- Select Staff Name --</option>
              <?php
              $staffList = $system->getStaff($db); // Make sure this returns an array of staff
              foreach ($staffList as $staff):
                  $fullName = $staff['firstname'] . ' ' . 
                              (!empty($staff['midname']) ? $staff['midname'] . ' ' : '') . 
                              $staff['surname'];
                  if (!empty($staff['extension'])) {
                      $fullName .= ', ' . $staff['extension'];
                  }
              ?>
                  <option value="<?php echo htmlspecialchars($fullName); ?>"
                          data-dept="<?php echo htmlspecialchars($staff['dept_id']); ?>">
                      <?php echo htmlspecialchars($fullName); ?>
                  </option>
              <?php endforeach; ?>
          </select>


          <input type="hidden" name="dept_id" id="update-dept_id">

          <!-- Username Field -->
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="update-username" placeholder="Enter username" required>
          </div>

          <!-- Password Field (Optional for Update) -->
          <div class="mb-3">
            <label class="form-label">Password (Leave blank to keep unchanged)</label>
            <input type="password" class="form-control" name="password" id="update-password" placeholder="Enter new password">
          </div>

          <!-- Status Field -->
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" id="update-status" required>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>

          <!-- Position Field -->
          <div class="mb-3">
            <label class="form-label">Position</label>
            <select class="form-select" name="position" id="update-position" required>
              <option value="System Admin">System Admin</option>
              <option value="PR Custodian">PR Custodian</option>
              <option value="Requestor">Requestor</option>
              <option value="Dept. Head">Department Head</option>
              <option value="Admin">Admin</option>
              <option value="Purchaser">Purchaser</option>
              <option value="Stockroom">Stockroom</option>
            </select>
          </div>

          <!-- Hidden modid Input -->
          <input type="hidden" name="modid" id="update-modid" value="0">

        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
          <button type="submit" class="btn btn-primary ms-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M12 5l0 14" />
              <path d="M5 12l14 0" />
            </svg>
            Update User
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

    <!-- Add success-->
    <div class="modal modal-blur fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-status bg-success"></div>
          <div class="modal-body text-center py-4">
            <!-- Download SVG icon from http://tabler-icons.io/i/circle-check -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
            <h3>Payment succedeed</h3>
            <div class="text-secondary">Your payment of $290 has been successfully submitted. Your invoice has been sent to support@tabler.io.</div>
          </div>
        </div>
      </div>
    </div>
    <!-- Add Failed-->
    <div class="modal modal-blur fade" id="modal-failed" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div class="modal-title text-danger">Action Failed</div>
            <div class="text-secondary">The user could not be added. Please try again.</div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Delete Confirmation-->
<div class="modal modal-blur fade" id="modal-delete-confirm" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-title text-danger">Are you sure?</div>
        <div class="text-secondary">Do you really want to delete this user? This action cannot be undone.</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteButton">Yes, delete user</button>
      </div>
    </div>
  </div>
</div>
<!-- Update Successful-->
<div class="modal modal-blur fade" id="modal-update-success" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-success"></div>
            <div class="modal-body text-center py-4">
                <!-- Download SVG icon from http://tabler-icons.io/i/circle-check -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                    <path d="M9 12l2 2l4 -4" />
                </svg>
                <h3>Update Successful</h3>
                <div class="text-secondary">The user details have been successfully updated.</div>
            </div>
        </div>
    </div>
</div>
<!-- Delete Successful-->
<div class="modal modal-blur fade" id="modal-delete-success" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-success"></div>
            <div class="modal-body text-center py-4">
                <!-- Download SVG icon from http://tabler-icons.io/i/circle-check -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                    <path d="M9 12l2 2l4 -4" />
                </svg>
                <h3>Delete Successful</h3>
                <div id="delete-success-message" class="text-secondary"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="staffModal" tabindex="-1" aria-labelledby="staffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 70%; width: auto !important;">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="staffModalLabel">List of Staff</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="staffTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Staff ID</th>
                                <th>Full Name</th>
                                <th>Department</th>
                                <th>Email</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="staffTableBody">
                            <!-- Data will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="addStaffBtn">
                    <i class="bi bi-person-plus"></i> Add Staff
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal modal-blur fade" id="editStaffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Staff Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editStaffForm">
                    <input type="hidden" id="editStaffId" name="staff_id">
                    <div class="mb-3">
                        <label class="form-label">Surname</label>
                        <input type="text" class="form-control" id="editSurname" name="surname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="editMidname" name="midname">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" id="editFirstname" name="firstname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Extension</label>
                        <input type="text" class="form-control" id="editExtension" name="extension">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <select class="form-select" id="editDeptId" name="dept_id">
                            <option value="">Select Department</option>
                            <!-- Departments will be populated by JavaScript -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="editStaffEmail" name="staff_email">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveStaffChanges">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Insert Staff Modal -->
<div class="modal modal-blur fade" id="addStaffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Add Staff Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addStaffForm">
                    <div class="mb-3">
                        <label class="form-label">Surname</label>
                        <input type="text" class="form-control" id="addSurname" name="surname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="addMidname" name="midname">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" id="addFirstname" name="firstname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Extension</label>
                        <input type="text" class="form-control" id="addExtension" name="extension">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Department</label>
                      <select class="form-select" id="addDeptId" name="dept_id" required>
                          <option value="">Select Department</option>
                          <?php
                          $departments = $system->getDepartments($db); // This should return dept_id, dept_name, dept_group
                          foreach ($departments as $dept):
                              $deptLabel = $dept['dept_name'];
                          ?>
                              <option value="<?php echo htmlspecialchars($dept['dept_id']); ?>">
                                  <?php echo htmlspecialchars($deptLabel); ?>
                              </option>
                          <?php endforeach; ?>
                      </select>
                  </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="addStaffEmail" name="staff_email">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveNewStaff">Add Staff</button>
            </div>
        </div>
    </div>
</div>



<script>
  document.getElementById('staffSelect').addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];
    const deptId = selectedOption.getAttribute('data-dept');
    document.getElementById('dept_id').value = deptId || '';
  });

  document.getElementById('addStaffBtn').addEventListener('click', function () {
    const insertModal = new bootstrap.Modal(document.getElementById('addStaffModal'));
    insertModal.show();
});
</script>
