<style>
.clickable-user-card { cursor: pointer; }  
.clickable-user-card:hover { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075); }  
.active-filter-card { border: 2px solid #007bff; }  
</style>
<div class="col-sm-6 col-lg-3">
    <div class="card card-sm clickable-user-card" id="totalUsersCard" data-status-filter="all">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <span class="bg-primary text-white avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                        </svg>
                    </span>
                </div>
                <div class="col">
                    <div class="h1 mb-0 me-2">
                        <span id="totalUsersCount"><?php echo $system->getTotalUsersCount($db); ?></span>
                    </div>
                    <div class="text-secondary">
                        Users
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-3">
    <div class="card card-sm clickable-user-card" id="activeUsersCard" data-status-filter="active">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <span class="bg-success text-white avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                            <path d="M15 19l2 2l4 -4" /></svg>
                    </span>
                </div>
                <div class="col">
                    <div class="h1 mb-0 me-2">
                        <span id="totalActiveUsersCount"><?php echo $system->getTotalActiveUsersCount($db); ?></span>
                    </div>
                    <div class="text-secondary">
                        Active Users
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-3">
    <div class="card card-sm clickable-user-card" id="inactiveUsersCard" data-status-filter="inactive">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <span class="bg-secondary text-white avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-cancel">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" />
                            <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M17 21l4 -4" /></svg>
                    </span>
                </div>
                <div class="col">
                    <div class="h1 mb-0 me-2">
                        <span id="totalInactiveUsersCount"><?php echo $system->getTotalInactiveUsersCount($db); ?></span>
                    </div>
                    <div class="text-secondary">
                        Inactive Users
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-3">
    <div class="card card-sm clickable-user-card" id="StaffCard" data-bs-toggle="modal" data-bs-target="#staffModal">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <span class="bg-info text-white avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-circle">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" /></svg>
                    </span>
                </div>
                <div class="col">
                    <div class="h1 mb-0 me-2">
                        <span id="totalStaff"><?php echo $system->getTotalStaffCount($db); ?></span>
                    </div>
                    <div class="text-secondary">
                        List of Staff
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>