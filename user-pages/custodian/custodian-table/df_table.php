<?php $modid = $_SESSION['modid']; ?>

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="dfTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="df-tab" data-bs-toggle="tab" data-bs-target="#df" type="button" role="tab">Deployment Forms</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="df-details-tab" data-bs-toggle="tab" data-bs-target="#df-details" type="button" role="tab">DF Details</button>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="dfTabsContent">
                
                <!-- Deployment Forms Tab -->
                <div class="tab-pane fade show active" id="df" role="tabpanel">
                    <div class="table-responsive">
                        <table id="dfTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>DF No.</th>
                                    <th>Staff</th>
                                    <th>Department</th>
                                    <th>Unit</th>
                                    <th>DF Date</th>
                                    <th>Requested By</th>
                                    <th>RR No.</th>
                                    <?php if ($modid != 1): ?>
                                    
                                    <?php endif; ?>      
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="dfTableBody">
                                <!-- DF rows via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- DF Details Tab -->
                <div class="tab-pane fade" id="df-details" role="tabpanel">
                    <div class="table-responsive">
                        <table id="dfDetailsTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>DF No.</th>
                                    <th>Item No.</th>
                                    <th>Particular</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Amount</th>
                                    <?php if ($modid != 6): ?>
                                        <th>Equipment No.</th>
                                    <?php endif; ?>
                                    <th>Last Updated</th>
                                    <!--<th>Actions</th>-->
                                </tr>
                            </thead>
                            <tbody id="dfDetailsTableBody">
                                <!-- DF Details rows via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
