let allUsersData = [];
let userTable = null;
let currentFilter = 'all';
let staffTableInstance = null;

function setActiveCard(cardId) {
    document.querySelectorAll('.clickable-user-card').forEach(card => {
        card.classList.remove('active-filter-card');
    });
    const activeCardElement = document.getElementById(cardId);
    if (activeCardElement) {
        activeCardElement.classList.add('active-filter-card');
    }
}

function applyTableFilter(statusFilter) {
    if (!userTable) return;
    currentFilter = statusFilter;
    let searchTerm = "";
    const tableTitleElement = document.getElementById('userTableTitle');

    if (statusFilter === 'active') {
        searchTerm = "^ Active$";
        setActiveCard('activeUsersCard');
        if (tableTitleElement) tableTitleElement.innerText = "Active User Accounts";
    } else if (statusFilter === 'inactive') {
        searchTerm = "^ Inactive$";
        setActiveCard('inactiveUsersCard');
        if (tableTitleElement) tableTitleElement.innerText = "Inactive User Accounts";
    } else {
        searchTerm = ""; // For 'all' or undefined
        setActiveCard('totalUsersCard');
        if (tableTitleElement) tableTitleElement.innerText = "All User Accounts";
    }
    userTable.column(4).search(searchTerm, true, false).draw();
}

function loadUsersAndApplyFilter(newFilter = null) {
    if (newFilter !== null) {
      currentFilter = newFilter;
    }
    fetch('sys-fetch/fetch_user.php', {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => {
        if (!response.ok) { throw new Error(`HTTP error! status: ${response.status}`); }
        return response.json();
    })
    .then(response => {
        if (response.success && Array.isArray(response.data)) {
            allUsersData = response.data;
            renderUserTable(allUsersData);
            applyTableFilter(currentFilter); // Apply the potentially updated currentFilter

            if(response.counts) {
                 if (document.getElementById('totalUsersCount') && response.counts.totalUsers !== undefined) document.getElementById('totalUsersCount').innerText = response.counts.totalUsers;
                 if (document.getElementById('totalActiveUsersCount') && response.counts.activeUsers !== undefined) document.getElementById('totalActiveUsersCount').innerText = response.counts.activeUsers;
                 if (document.getElementById('totalInactiveUsersCount') && response.counts.inactiveUsers !== undefined) document.getElementById('totalInactiveUsersCount').innerText = response.counts.inactiveUsers;
            }

        } else {
            console.error('Invalid response structure for users:', response);
            const tableBody = document.getElementById('userTableBody');
            if (tableBody) tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Failed to load users</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error fetching users:', error);
        const tableBody = document.getElementById('userTableBody');
        if (tableBody) tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Error fetching users</td></tr>';
    });
}

function renderUserTable(users) {
    if ($.fn.DataTable.isDataTable('#userTable')) {
        $('#userTable').DataTable().destroy();
    }
    const tableBody = document.getElementById('userTableBody');
    tableBody.innerHTML = '';
    if (users.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">No users found</td></tr>';
    } else {
        users.forEach((user, index) => {
            const statusText = user.status === '1' ? 'Active' : 'Inactive';
            const statusBadge = user.status === '1'
                ? `<span class="badge bg-success me-1"></span> ${statusText}`
                : `<span class="badge bg-secondary me-1"></span> ${statusText}`;
            tableBody.innerHTML += `
                <tr>
                    <td><span class="text-secondary">${index + 1}</span></td>
                    <td><a href="#" class="text-reset" tabindex="-1">${user.name}</a></td>
                    <td>${user.username}</td>
                    <td>${user.position}</td>
                    <td>${statusBadge}</td>
                    <td class="text-center">
                        <button class="btn btn-md btn-link update-user" data-uaid="${user.uaid}"
                                style="padding: 0; margin: 0; background: transparent; border: none; display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                <path d="M16 5l3 3"/>
                            </svg>
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    userTable = $('#userTable').DataTable({
        responsive: true, paging: true, searching: true, ordering: true, order: [[0, 'asc']],
        columnDefs: [{ targets: [5], orderable: false }],
        dom: `
            <'row mb-3'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'><'col-sm-12 col-md-3 d-flex justify-content-end'f>>
            <'row'<'col-sm-12'tr>>
            <'row mt-2'<'col-sm-12 col-md-5 d-flex align-items-center'i><'col-sm-12 col-md-7 d-flex justify-content-end'p>>
        `,
        lengthMenu: [10, 25, 50, 100],
        language: { search: "_INPUT_", searchPlaceholder: "Search user name..." }
    });
}

function loadStaffData() {
    fetch('sys-fetch/fetch_staff.php', {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => {
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return response.json();
    })
    .then(data => {
        const staffTable = $('#staffTable');
        
        if (data.success && Array.isArray(data.data)) {
            const formattedData = data.data.map(staff => ({
                staff_id: staff.staff_id || '',
                full_name: staff.full_name || '',
                dept_name: staff.dept_name || 'No Department',
                staff_email: staff.staff_email || ''
            }));

            if ($.fn.DataTable.isDataTable('#staffTable')) {
                staffTable.DataTable().clear().rows.add(formattedData).draw();
            } else {
                staffTable.DataTable({
                    data: formattedData,
                    responsive: true,
                    paging: true,
                    searching: true,
                    ordering: true,
                    destroy: true,
                    lengthMenu: [10, 25, 50, 100],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search Staff Name..."
                    },
                    columns: [
                        { data: 'staff_id', width: '10%' },
                        { data: 'full_name', width: '25%' },
                        { data: 'dept_name', width: '25%' },
                        { data: 'staff_email', width: '25%' },
                        {
                            data: 'staff_id',
                            orderable: false,
                            width: '15%',
                            render: function(data) {
                                return `<button class="btn btn-sm btn-primary edit-staff" data-id="${data}">Edit</button>`;
                            }
                        }
                    ]
                });
            }

            // Rebind edit button event using delegation
            $('#staffTable tbody').off('click', '.edit-staff').on('click', '.edit-staff', function () {
                const staffId = $(this).data('id');
                editStaff(staffId);
            });

        } else {
            console.error('Error loading staff data or invalid format:', data.message || data);
            document.getElementById('staffTableBody').innerHTML = `
                <tr><td colspan="5" class="text-center">Failed to load staff data. ${data.message || ''}</td></tr>`;
        }
    })
    .catch(error => {
        console.error('Error fetching staff data:', error);
        const staffTableBody = document.getElementById('staffTableBody');
        if (staffTableBody) {
            staffTableBody.innerHTML = `
                <tr><td colspan="5" class="text-center">Error fetching staff data: ${error.message}</td></tr>`;
        }
    });
}

document.getElementById('saveStaffChanges').addEventListener('click', () => {
    const staffId = document.getElementById('editStaffId').value;
    const surname = document.getElementById('editSurname').value;
    const midname = document.getElementById('editMidname').value;
    const firstname = document.getElementById('editFirstname').value;
    const extension = document.getElementById('editExtension').value;
    const deptId = document.getElementById('editDeptId').value;
    const staffEmail = document.getElementById('editStaffEmail').value;

    // Build the form data
    const formData = new FormData();
    formData.append('staff_id', staffId);
    formData.append('surname', surname);
    formData.append('midname', midname);
    formData.append('firstname', firstname);
    formData.append('extension', extension);
    formData.append('dept_id', deptId);
    formData.append('staff_email', staffEmail);

    // Send the POST request to update_staff.php
    fetch('sys-fetch/update_staff.php', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            $('#editStaffModal').modal('hide');

            // Optionally reload the staff table
            loadStaffData();
            loadUsersAndApplyFilter();

            // Show success notification
            alert('Staff record updated successfully.');
        } else {
            alert('Failed to update staff: ' + (data.message || 'Unknown error.'));
        }
    })
    .catch(error => {
        console.error('Error updating staff:', error);
        alert('An error occurred while updating staff.');
    });
});

function setupAddStaffForm() {
    const form = document.getElementById('addStaffForm');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const res = await fetch('sys-fetch/insert_staff.php', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });

            const data = await res.json();

            if (data.success) {
                alert(data.message || 'Staff member added successfully!');
                this.reset();

                const modalEl = document.getElementById('addStaffModal');
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

                modalEl.addEventListener('hidden.bs.modal', function handler() {
                    loadStaffData();
                    modalEl.removeEventListener('hidden.bs.modal', handler);
                });

                modal.hide();
            } else {
                alert(data.message || 'Failed to add staff member.');
            }
        } catch (err) {
            alert('A network or server error occurred.');
        }
    });
}




function editStaff(staffId) {
    fetch(`sys-fetch/get_staff.php?id=${staffId}`, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const staff = data.data;
            const departments = data.departments || [];

            // Populate input fields
            document.getElementById('editStaffId').value = staff.staff_id;
            document.getElementById('editSurname').value = staff.surname;
            document.getElementById('editMidname').value = staff.midname;
            document.getElementById('editFirstname').value = staff.firstname;
            document.getElementById('editExtension').value = staff.extension;
            document.getElementById('editStaffEmail').value = staff.staff_email;

            // Populate department dropdown
            const deptSelect = document.getElementById('editDeptId');
            deptSelect.innerHTML = ''; // Clear existing options

            departments.forEach(dept => {
                const option = document.createElement('option');
                option.value = dept.dept_id;
                option.textContent = dept.dept_name;
                if (dept.dept_id === staff.dept_id) {
                    option.selected = true;
                }
                deptSelect.appendChild(option);
            });

            // Show the modal
            $('#editStaffModal').modal('show');
        } else {
            alert('Error loading staff details: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error loading staff details');
    });
}

function addUser(event) {
    event.preventDefault();
    const formData = new FormData(event.target);

    fetch('sys-fetch/insert_user.php', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('addUserForm').reset();
            const addUserModalEl = document.getElementById('modal-add-user');
            if (addUserModalEl) {
                const addUserModal = bootstrap.Modal.getInstance(addUserModalEl);
                if (addUserModal) addUserModal.hide();
            }

            const successModalEl = document.getElementById('modal-success');
            const successModal = new bootstrap.Modal(successModalEl);
            if (document.querySelector('#modal-success .modal-body h3'))
                document.querySelector('#modal-success .modal-body h3').innerText = "User Added Successfully";
            if (document.querySelector('#modal-success .modal-body .text-secondary'))
                document.querySelector('#modal-success .modal-body .text-secondary').innerText = `The user "${formData.get('name')}" has been successfully added.`;
            successModal.show();

            successModalEl.addEventListener('hidden.bs.modal', function () {
                loadUsersAndApplyFilter('all'); // Reload users and apply default 'all' filter
            }, { once: true });
        } else {


            const failedModalEl = document.getElementById('modal-failed');
            const failedModal = new bootstrap.Modal(failedModalEl);
            if (document.querySelector('#modal-failed .modal-body .text-secondary'))
                document.querySelector('#modal-failed .modal-body .text-secondary').innerText = data.message || "An unknown error occurred.";
            failedModal.show();
        }
    })
    .catch(error => {
        console.error('Error adding user:', error);
        const failedModalEl = document.getElementById('modal-failed');
        const failedModal = new bootstrap.Modal(failedModalEl);
        if (document.querySelector('#modal-failed .modal-body .text-secondary'))
            document.querySelector('#modal-failed .modal-body .text-secondary').innerText = "An error occurred. Please try again.";
        failedModal.show();
    });
}


function deleteUser(uaid) {
    const deleteModalEl = document.getElementById('modal-delete-confirm');
    const deleteModal = new bootstrap.Modal(deleteModalEl);
    deleteModal.show();
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');
    confirmDeleteButton.onclick = async function () {
        deleteModal.hide();
        try {
            const userToDelete = allUsersData.find(user => user.uaid === uaid);
            const userName = userToDelete ? userToDelete.name : 'Unknown User';
            const response = await fetch('sys-fetch/delete_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body: `uaid=${encodeURIComponent(uaid)}`
            });
            const data = await response.json();
            if (data.success) {
                const successModalEl = document.getElementById('modal-delete-success');
                const successModal = new bootstrap.Modal(successModalEl);
                if(document.getElementById('delete-success-message')) document.getElementById('delete-success-message').textContent = `The user "${userName}" has been successfully deleted.`;
                successModal.show();
                successModalEl.addEventListener('hidden.bs.modal', function () {
                    loadUsersAndApplyFilter('all'); // Reload users and apply default 'all' filter
                }, { once: true });
            } else {
                alert('Error deleting user: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error deleting user:', error);
            alert('An error occurred during deletion.');
        }
        confirmDeleteButton.onclick = null;
    };
}

document.addEventListener('DOMContentLoaded', function () {
    const positionToModidMap = {
        'PR Custodian': '1', 'Requestor': '2', 'Dept. Head': '3',
        'Admin': '4', 'Purchaser': '5', 'Stockroom': '6',
    };
    const updateUserModalElement = document.getElementById('modal-update-user');
    const updateUserModal = bootstrap.Modal.getInstance(updateUserModalElement) || new bootstrap.Modal(updateUserModalElement);

    document.body.addEventListener('click', function(event) {
        const updateUserButton = event.target.closest('.update-user');
        if (updateUserButton) {
            event.preventDefault();
            const uaid = updateUserButton.dataset.uaid;
            if (uaid) { openUpdateUserModal(uaid); } else { alert('UAID missing.'); }
        }
    });

    const updatePositionDropdown = document.getElementById('update-position');
    const updateModidInput = document.getElementById('update-modid');
    if (updatePositionDropdown && updateModidInput) {
        updatePositionDropdown.addEventListener('change', function () {
            updateModidInput.value = positionToModidMap[this.value] || '';
        });
    }

    function openUpdateUserModal(uaid) {
        fetch('sys-fetch/fetch_user_details.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body: new URLSearchParams({ uaid: uaid })
        })
        .then(response => response.ok ? response.json() : Promise.reject('Error fetching user details'))
        .then(data => {
            if (data.success && data.data) {
                document.getElementById('update-uaid').value = data.data.uaid;
                document.getElementById('update-name').value = data.data.name;
                document.getElementById('update-username').value = data.data.username;
                document.getElementById('update-status').value = data.data.status;
                document.getElementById('update-position').value = data.data.position;
                updateModidInput.value = positionToModidMap[data.data.position] || data.data.modid || '';
                document.getElementById('update-password').value = '';
                if (updateUserModal) updateUserModal.show();
            } else {
                alert('Error fetching user details: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Fetch Error for user details:', error);
            alert('Failed to fetch user details.');
        });
    }

    const updateUserForm = document.getElementById('updateUserForm');
    if (updateUserForm) {
        updateUserForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);
            if (!document.getElementById('update-password').value.trim()) {
                formData.delete('password');
            }
            formData.set('modid', document.getElementById('update-modid').value);
            fetch('sys-fetch/update_user.php', {
                method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: formData
            })
            .then(response => response.ok ? response.json() : Promise.reject('Error updating user'))
            .then(data => {
                if (data.success) {
                    if (updateUserModal) updateUserModal.hide();
                    const successModalEl = document.getElementById('modal-update-success');
                    const successModal = new bootstrap.Modal(successModalEl);
                    if(document.querySelector('#modal-update-success .modal-body h3')) document.querySelector('#modal-update-success .modal-body h3').innerText = "User Updated Successfully";
                    if(document.querySelector('#modal-update-success .modal-body .text-secondary')) document.querySelector('#modal-update-success .modal-body .text-secondary').innerText = `The user "${formData.get('username')}" has been successfully updated.`;
                    successModalEl.addEventListener('hidden.bs.modal', () => {
                         loadUsersAndApplyFilter(document.getElementById('update-status').value === '1' ? 'active' : 'inactive');
                    }, { once: true });
                    successModal.show();
                } else {
                    alert('Error updating user: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Update User Error:', error);
                alert('Failed to update user: ' + (error.message || error));
            });
        });
    }

    const createPositionDropdown = document.getElementById('position');
    const createModidInput = document.getElementById('modid');
    if (createPositionDropdown && createModidInput) {
        createPositionDropdown.addEventListener('change', function () {
            createModidInput.value = positionToModidMap[this.value] || '';
        });
    }
    const updateNameDropdown = document.getElementById('update-name');
    if (updateNameDropdown && document.getElementById('update-dept_id')) {
        updateNameDropdown.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const deptId = selected.getAttribute('data-dept') || '';
            document.getElementById('update-dept_id').value = deptId;
        });
    }

    loadUsersAndApplyFilter('all'); // Initial load of users with 'all' filter

    document.querySelectorAll('.clickable-user-card').forEach(card => {
        // Check if the card is not the StaffCard before adding the filter listener
        if (card.id !== 'StaffCard') {
            card.addEventListener('click', function() {
                const statusFilter = this.dataset.statusFilter;
                if (statusFilter !== undefined) { // Only apply if data-status-filter exists
                    applyTableFilter(statusFilter);
                }
            });
        }
    });

    const staffCardElement = document.getElementById('StaffCard');
    if (staffCardElement) {
        staffCardElement.addEventListener('click', function() {
            loadStaffData(); // Load data when card is clicked
            const staffModalElement = document.getElementById('staffModal');
            if (staffModalElement) {
                const staffModal = bootstrap.Modal.getInstance(staffModalElement) || new bootstrap.Modal(staffModalElement);
                staffModal.show();
            }
        });
    }
});

$(document).ready(function () {
    const addUserFormElement = document.getElementById('addUserForm');
    if(addUserFormElement) {
        addUserFormElement.addEventListener('submit', addUser);
    }
    $(document).on('click', '.delete-user', function () {
        const uaid = $(this).data('uaid');
        if (uaid) deleteUser(uaid);
    });
});

function applyTableFilter(statusFilter) {
    if (!userTable) return;
    currentFilter = statusFilter;
    let searchTerm = "";
    const tableTitleElement = document.getElementById('userTableTitle'); // Get the title element

    if (statusFilter === 'active') {
        searchTerm = "^ Active$";
        setActiveCard('activeUsersCard');
        if (tableTitleElement) tableTitleElement.innerText = "Active User Accounts"; // Update title
    } else if (statusFilter === 'inactive') {
        searchTerm = "^ Inactive$";
        setActiveCard('inactiveUsersCard');
        if (tableTitleElement) tableTitleElement.innerText = "Inactive User Accounts"; // Update title
    } else {
        searchTerm = "";
        setActiveCard('totalUsersCard');
        if (tableTitleElement) tableTitleElement.innerText = "All User Accounts"; // Update title
    }
    userTable.column(4).search(searchTerm, true, false).draw();
}

document.addEventListener('DOMContentLoaded', function() {
    setupAddStaffForm();
});

document.getElementById('saveNewStaff').addEventListener('click', function () {
    document.getElementById('addStaffForm').requestSubmit();
});