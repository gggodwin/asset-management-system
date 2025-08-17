<?php
$requested_by = $_SESSION['name'];  // Get the logged-in user's name
$modid = $_SESSION['modid'];        // Get the user's modid

// Fetch PR stats based on the user's role (admin or requestor)
$stats = $requestor->getUserPRStats($db, $requested_by, $modid);
$incomingStats = $requestor->getIncomingAndCompletedStats($db, $modid, $requested_by);

?>
            <div class="col-sm-6 col-lg-3">
              <div class="card card-sm" id="totalPRsCard">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <span class="bg-primary text-white avatar">
                        <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                         <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-notes"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 3m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" /><path d="M9 7l6 0" /><path d="M9 11l6 0" /><path d="M9 15l4 0" /></svg>
                        </svg>
                      </span>
                    </div>
                    <div class="col">
                      <div class="h1 mb-0 me-2">
                        <span id="totalPRsCount1"><?php echo $stats['total_prs']  ?></span>
                      </div>
                      <div class="text-secondary">
                        Total PRS
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-6 col-lg-3">
              <div class="card card-sm" id="pendingPRsCard">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <span class="bg-secondary text-white avatar">
                        <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-dots"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 14v.01" /><path d="M12 14v.01" /><path d="M15 14v.01" /></svg>
                      </span>
                    </div>
                    <div class="col">
                      <div class="h1 mb-0 me-2">
                        <span id="pendingPRsCount"><?php echo $stats['pending_prs'] ?? 0 ?></span>
                      </div>
                      <div class="text-secondary">
                        For Admin Approval
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-6 col-lg-3">
              <div class="card card-sm" id="approvedPRsCard">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <span class="bg-success text-white avatar">
                        <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg>
                      </span>
                    </div>
                    <div class="col">
                      <div class="h1 mb-0 me-2">
                        <span id="approvedPRsCount"><?php echo $stats['approved_prs'] ?? 0 ?></span>
                      </div>
                      <div class="text-secondary">
                        Approved PRS
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>


              
              <div class="col-sm-6 col-lg-3">
                <div class="card card-sm" id="incomingCompletedCard" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#prsModal">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-warning text-white avatar">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icon-tabler-package">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M3 9l9 6l9 -6" />
                            <path d="M3 9l9 -6l9 6" />
                            <path d="M3 9v6l9 6l9 -6v-6" />
                            <path d="M12 3v12" />
                          </svg>
                        </span>
                      </div>
                      <div class="col">
                        <div class="h1 mb-0 me-2">
                          <span id="incomingItemsCount">
                            <?php echo $incomingStats['incoming_items'] ?? 0; ?>
                          </span>
                          /
                          <span id="completedItemsCount">
                            <?php echo $incomingStats['completed_items'] ?? 0; ?>
                          </span>
                        </div>
                        <div class="text-secondary">
                          Incoming / Completed Items
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
