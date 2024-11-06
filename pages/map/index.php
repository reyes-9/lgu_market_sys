<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/map.css">
    <!-- maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="icon" type="image/png" href="../../images/favicon_192.png">
    <title>Vendor Mapping - Public Market Monitoring System</title>
</head>

<body class="body light">
    <?php include '../../includes/nav.php'; ?>

    <div class="content-wrapper">

        <div class="container-fluid">
            <h2 class="text-center mt-3"><strong>Vendor Mapping</strong></h2>
            <div class="row m-5 p-5 shadow rounded-3 mapping">
                <div class="container">

                    <div class="row justify-content-between m-3 p-3">
                        <!-- Left Section: Map and Select Market -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="market">Market:</label>
                                    <select class="form-select" id="market" name="market" required>
                                        <option value="">-- Select Market --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="info-box my-3">
                                <div class="header">Address</div>
                                <div class="content" id="market_address"></div>
                                <div class="header">Market Info</div>
                                <div class="content">Stall Count: <span id="stall_count"></span></div>
                                <div class="content">Vacant: <span id="stall_vacant"></span></div>
                                <div class="content">Occupied: <span id="stall_occupied"></span></div>
                            </div>
                            <div id="responseContainer"></div>
                            <div>
                                <button class="btn btn-warning mb-3" id="viewStallsBtn" onclick=showStallMap() disabled>View Stalls</button>
                            </div>

                        </div>

                        <div class="col-md-5">
                            <div id="map" class="mb-3">
                                <p class="text-center">Map Placeholder</p>
                            </div>
                        </div>
                    </div>

                    <div class="row m-5 p-0 map-section" id="map_section">

                        <div class="container text-center my-5">
                            <h2>Market Stalls Map</h2>

                            <svg width="800" height="600" xmlns="http://www.w3.org/2000/svg">
                                <!-- 1x7 Red Stall (Single Row of 7 Squares) -->
                                <g fill="#ff4d4d" stroke="#000" stroke-width="1">
                                    <rect x="10" y="10" width="20" height="20" class="stall" data-stall-id="1 Aling Nenas's Stall" />
                                    <rect x="30" y="10" width="20" height="20" class="stall" />
                                    <rect x="50" y="10" width="20" height="20" class="stall" />
                                    <rect x="70" y="10" width="20" height="20" class="stall" />
                                    <rect x="90" y="10" width="20" height="20" class="stall" />
                                    <rect x="110" y="10" width="20" height="20" class="stall" />
                                    <rect x="130" y="10" width="20" height="20" class="stall" />
                                </g>

                                <!-- 2x5 Green Stall (Two Rows of 5 Squares Each) -->
                                <g fill="#4dff4d" stroke="#000" stroke-width="1">
                                    <rect x="10" y="50" width="20" height="20" />
                                    <rect x="30" y="50" width="20" height="20" />
                                    <rect x="50" y="50" width="20" height="20" />
                                    <rect x="70" y="50" width="20" height="20" />
                                    <rect x="90" y="50" width="20" height="20" />
                                    <rect x="10" y="70" width="20" height="20" />
                                    <rect x="30" y="70" width="20" height="20" />
                                    <rect x="50" y="70" width="20" height="20" />
                                    <rect x="70" y="70" width="20" height="20" />
                                    <rect x="90" y="70" width="20" height="20" />
                                </g>

                                <!-- 2x8 Green Stall (Two Rows of 8 Squares Each) -->
                                <g fill="#66ff66" stroke="#000" stroke-width="1">
                                    <rect x="10" y="110" width="20" height="20" />
                                    <rect x="30" y="110" width="20" height="20" />
                                    <rect x="50" y="110" width="20" height="20" />
                                    <rect x="70" y="110" width="20" height="20" />
                                    <rect x="90" y="110" width="20" height="20" />
                                    <rect x="110" y="110" width="20" height="20" />
                                    <rect x="130" y="110" width="20" height="20" />
                                    <rect x="150" y="110" width="20" height="20" />
                                    <rect x="10" y="130" width="20" height="20" />
                                    <rect x="30" y="130" width="20" height="20" />
                                    <rect x="50" y="130" width="20" height="20" />
                                    <rect x="70" y="130" width="20" height="20" />
                                    <rect x="90" y="130" width="20" height="20" />
                                    <rect x="110" y="130" width="20" height="20" />
                                    <rect x="130" y="130" width="20" height="20" />
                                    <rect x="150" y="130" width="20" height="20" />
                                </g>

                                <!-- 2x8 Gray Stall (Two Rows of 8 Squares Each) -->
                                <g fill="#b3b3b3" stroke="#000" stroke-width="1">
                                    <rect x="10" y="170" width="20" height="20" />
                                    <rect x="30" y="170" width="20" height="20" />
                                    <rect x="50" y="170" width="20" height="20" />
                                    <rect x="70" y="170" width="20" height="20" />
                                    <rect x="90" y="170" width="20" height="20" />
                                    <rect x="110" y="170" width="20" height="20" />
                                    <rect x="130" y="170" width="20" height="20" />
                                    <rect x="150" y="170" width="20" height="20" />
                                    <rect x="10" y="190" width="20" height="20" />
                                    <rect x="30" y="190" width="20" height="20" />
                                    <rect x="50" y="190" width="20" height="20" />
                                    <rect x="70" y="190" width="20" height="20" />
                                    <rect x="90" y="190" width="20" height="20" />
                                    <rect x="110" y="190" width="20" height="20" />
                                    <rect x="130" y="190" width="20" height="20" />
                                    <rect x="150" y="190" width="20" height="20" />
                                </g>


                                <!-- 2x2 Gray Stall (Two Rows of 2 Squares Each) -->
                                <g fill="#d9d9d9" stroke="#000" stroke-width="1">
                                    <rect x="10" y="230" width="20" height="20" />
                                    <rect x="30" y="230" width="20" height="20" />
                                    <rect x="10" y="250" width="20" height="20" />
                                    <rect x="30" y="250" width="20" height="20" />
                                </g>

                                <!-- 2x7 Yellow Stall (Two Rows of 7 Squares Each) -->
                                <g fill="#ffff4d" stroke="#000" stroke-width="1">
                                    <rect x="10" y="290" width="20" height="20" />
                                    <rect x="30" y="290" width="20" height="20" />
                                    <rect x="50" y="290" width="20" height="20" />
                                    <rect x="70" y="290" width="20" height="20" />
                                    <rect x="90" y="290" width="20" height="20" />
                                    <rect x="110" y="290" width="20" height="20" />
                                    <rect x="130" y="290" width="20" height="20" />
                                    <rect x="10" y="310" width="20" height="20" />
                                    <rect x="30" y="310" width="20" height="20" />
                                    <rect x="50" y="310" width="20" height="20" />
                                    <rect x="70" y="310" width="20" height="20" />
                                    <rect x="90" y="310" width="20" height="20" />
                                    <rect x="110" y="310" width="20" height="20" />
                                    <rect x="130" y="310" width="20" height="20" />
                                </g>

                                <!-- 2x8 Yellow Stall (Two Rows of 8 Squares Each) -->
                                <g fill="#ffff66" stroke="#000" stroke-width="1">
                                    <rect x="10" y="350" width="20" height="20" />
                                    <rect x="30" y="350" width="20" height="20" />
                                    <rect x="50" y="350" width="20" height="20" />
                                    <rect x="70" y="350" width="20" height="20" />
                                    <rect x="90" y="350" width="20" height="20" />
                                    <rect x="110" y="350" width="20" height="20" />
                                    <rect x="130" y="350" width="20" height="20" />
                                    <rect x="150" y="350" width="20" height="20" />
                                    <rect x="10" y="370" width="20" height="20" />
                                    <rect x="30" y="370" width="20" height="20" />
                                    <rect x="50" y="370" width="20" height="20" />
                                    <rect x="70" y="370" width="20" height="20" />
                                    <rect x="90" y="370" width="20" height="20" />
                                    <rect x="110" y="370" width="20" height="20" />
                                    <rect x="130" y="370" width="20" height="20" />
                                    <rect x="150" y="370" width="20" height="20" />
                                </g>
                            </svg>

                            <!-- Modal Structure -->
                            <div class="modal fade" id="stallModal" tabindex="-1" aria-labelledby="stallModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="stallModalLabel">Stall 102</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Vendor:</th>
                                                        <td>Nelson Reyes</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">Market Section:</th>
                                                        <td>Vegetables</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Stall No.:</th>
                                                        <td>102</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Stall Size:</th>
                                                        <td>108 sq/m</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Stall Rent:</th>
                                                        <td>₱400.00</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- <svg version="1.1" viewBox="0 0 1080 1080" width="500" height="500" xmlns="http://www.w3.org/2000/svg">
                                <path transform="translate(0)" d="m0 0h1080v1080h-1080z" fill="#D4D4D4" />
                                <path transform="translate(37,28)" d="m0 0h296v7l-117 1h-52l-130-1 1 11v189l1 39v28l1 5-1 1-1 175h2v8h108l1 54v157l-1 104h11v-315h106l1 138v182l10 1v-321h109v310l10-1v-235l1-74h202v350h-561v10h440v2h160v91h-601v10h601v76h-600v10h600v16h-596l-6-5-1-3v-1012l3-6z" fill="#E9E9E9" />
                                <path transform="translate(0)" d="m0 0h1080v1014h-56v-161l22-1v-10h-3v-143h7v-10h-238v-92h236v-10h-235l-1-1v-92h185l46 1v-403h7v-10h-47l-1-46h-336v-8h-11v7l-115 1h-157l-40-1v-7l-306 1-4 4-1 3v1012l3 5 3 2 596 1v24h-634z" fill="#FDFDFD" />
                                <path transform="translate(606,491)" d="m0 0h60v3h136v330h10v-124l158-1h54l19 1v142h-437z" fill="#E9E9E9" />
                                <path transform="translate(1e3 93)" d="m0 0h40v391h-275l-102-1v-97l336-1z" fill="#EAEAEA" />
                                <path transform="translate(666,271)" d="m0 0h326v105h-326z" fill="#E9E9E9" />
                                <path transform="translate(666,156)" d="m0 0h326v105h-326z" fill="#E9E9E9" />
                                <path transform="translate(164,111)" d="m0 0h53l116 1v112h-287v-50h286v-11l-116 1-170-1v-51z" fill="#E9E9E9" />
                                <path transform="translate(719,403)" d="m0 0h209l103 1v69h-104l-208-1z" fill="#FFDE59" />
                                <path transform="translate(343,235)" d="m0 0h312l1 29-1 37h-312z" fill="#E9E9E9" />
                                <path transform="translate(645,853)" d="m0 0h127v161h-127z" fill="#EAEAEA" />
                                <path transform="translate(343,311)" d="m0 0h114v170h-114z" fill="#EAEAEA" />
                                <path transform="translate(680,520)" d="m0 0h69l1 70v173l-1 33h-69l-1-68v-207z" fill="#FEDE59" />
                                <path transform="translate(164,234)" d="m0 0h53l116 1v66h-287v-66z" fill="#E9E9E9" />
                                <path transform="translate(222,311)" d="m0 0h111v169l-1 1h-110z" fill="#EAEAEA" />
                                <path transform="translate(782,853)" d="m0 0h117v161h-117z" fill="#EAEAEA" />
                                <path transform="translate(60,891)" d="m0 0h313l207 1v34h-519l-1-1z" fill="#CB6CE6" />
                                <path transform="translate(58,972)" d="m0 0h209l312 1v34h-313l-208-1z" fill="#CB6CE6" />
                                <path transform="translate(555,311)" d="m0 0h100l1 4v166h-101z" fill="#E9E9E9" />
                                <path transform="translate(343,46)" d="m0 0h312l1 8-1 47h-312z" fill="#E9E9E9" />
                                <path transform="translate(909,853)" d="m0 0h105v161h-105z" fill="#E9E9E9" />
                                <path transform="translate(701,174)" d="m0 0h244v69h-244z" fill="#7ED857" />
                                <path transform="translate(702,290)" d="m0 0h243v69h-244v-68z" fill="#7ED857" />
                                <path transform="translate(702,58)" d="m0 0h243v69h-244v-68z" fill="#7ED857" />
                                <path transform="translate(666,46)" d="m0 0h326v99l-172 1h-108l-46-1zm36 12-1 1v68h244v-69z" fill="#E9E9E9" />
                                <path transform="translate(823,712)" d="m0 0h209v69h-209z" fill="#FFDE59" />
                                <path transform="translate(824,607)" d="m0 0h209v69h-209z" fill="#FFDE59" />
                                <path transform="translate(824,507)" d="m0 0h209v69h-209z" fill="#FFDE59" />
                                <path transform="translate(46,311)" d="m0 0h79l1 52v52l-1 66h-79z" fill="#E9E9E9" />
                                <path transform="translate(467,311)" d="m0 0h78v170h-78z" fill="#E9E9E9" />
                                <path transform="translate(373,243)" d="m0 0h244v35h-244l-1-1v-33z" fill="#FF3F3F" />
                                <path transform="translate(58,243)" d="m0 0h244l1 1v33l-1 1h-244z" fill="#FF5757" />
                                <path transform="translate(373,58)" d="m0 0h244v35h-244l-1-1v-33z" fill="#FF3F3F" />
                                <path transform="translate(58,58)" d="m0 0h244l1 1v33l-1 1h-244z" fill="#FF5757" />
                                <path transform="translate(373,181)" d="m0 0h244v35h-245v-34z" fill="#FF3434" />
                                <path transform="translate(59,181)" d="m0 0h243l1 1v34h-245v-34z" fill="#FF5757" />
                                <path transform="translate(372,120)" d="m0 0h245v35h-244l-1-1z" fill="#FF3434" />
                                <path transform="translate(58,120)" d="m0 0h245v34l-1 1h-243l-1-1z" fill="#FF5757" />
                                <path transform="translate(343,111)" d="m0 0h312l1 48-1 4-111 1h-104l-97-1zm29 9v34l1 1h244v-35z" fill="#E9E9E9" />
                                <path transform="translate(46,46)" d="m0 0h287v55h-287zm12 12v35h244l1-1v-33l-1-1z" fill="#E9E9E9" />
                                <path transform="translate(343,174)" d="m0 0h312l1 37-1 13-244 1h-26l-42-1zm30 7-1 1v34h245v-35z" fill="#E9E9E9" />
                                <path transform="translate(645,1025)" d="m0 0h127v55h-128v-24z" fill="#EAEAEA" />
                                <path transform="translate(812,494)" d="m0 0h185l46 1v91l-63 1h-167l-1-1zm12 13v69h209v-69z" fill="#EAEAEA" />
                                <path transform="translate(812,597)" d="m0 0h231v92h-231zm12 10v69h209v-69z" fill="#EAEAEA" />
                                <path transform="translate(390,334)" d="m0 0h57v114h-57z" fill="#7ED857" />
                                <path transform="translate(233,334)" d="m0 0h57v114h-57z" fill="#7ED857" />
                                <path transform="translate(565,334)" d="m0 0h57v114h-57z" fill="#7ED957" />
                                <path transform="translate(146,334)" d="m0 0h57v114h-57z" fill="#7ED857" />
                                <path transform="translate(59,334)" d="m0 0h57v28l-1 86h-57v-85z" fill="#7ED957" />
                                <path transform="translate(478,334)" d="m0 0h56v114h-57v-85z" fill="#7ED857" />
                                <path transform="translate(782,1025)" d="m0 0h117v55h-117z" fill="#EAEAEA" />
                                <path transform="translate(136,311)" d="m0 0h75l1 105v26l-1 39h-75zm10 23v114h57v-114z" fill="#E9E9E9" />
                                <path transform="translate(909,1025)" d="m0 0h105v55h-105z" fill="#EAEAEA" />
                                <path transform="translate(452,730)" d="m0 0h69v68h-69z" fill="#545454" />
                                <path transform="translate(175,663)" d="m0 0h69v68h-69z" fill="#545454" />
                                <path transform="translate(59,663)" d="m0 0h69v68h-69z" fill="#545454" />
                                <path transform="translate(175,559)" d="m0 0h69v68h-69z" fill="#545454" />
                                <path transform="translate(59,559)" d="m0 0h69v68h-69z" fill="#545454" />
                                <path transform="translate(452,557)" d="m0 0h69v68h-69z" fill="#545454" />
                                <path transform="translate(666,28)" d="m0 0h372l5 6v48h-40l-1-46h-336z" fill="#EAEAEA" />
                                <path transform="translate(1043,1024)" d="m0 0h37v56h-56v-55z" fill="#FDFDFD" />
                                <path transform="translate(644,1056)" d="m0 0h128v24h-128z" fill="#FDFDFD" />
                                <path transform="translate(1024,853)" d="m0 0h19v161h-19z" fill="#EAEAEA" />
                                <path transform="translate(782,1056)" d="m0 0h117v24h-117z" fill="#FEFEFE" />
                                <path transform="translate(909,1056)" d="m0 0h105v24h-105z" fill="#FEFEFE" />
                                <path transform="translate(923,938)" d="m0 0h69v34h-69z" fill="#545454" />
                                <path transform="translate(175,732)" d="m0 0h69v34h-69z" fill="#545454" />
                                <path transform="translate(59,732)" d="m0 0h69v34h-69z" fill="#545454" />
                                <path transform="translate(452,695)" d="m0 0h69v34h-69z" fill="#545454" />
                                <path transform="translate(175,628)" d="m0 0h69v34h-69z" fill="#545454" />
                                <path transform="translate(59,628)" d="m0 0h69v34h-69z" fill="#545454" />
                                <path transform="translate(452,626)" d="m0 0h69v34h-69z" fill="#545454" />
                                <path transform="translate(175,524)" d="m0 0h69v34h-69z" fill="#545454" />
                                <path transform="translate(59,524)" d="m0 0h69v34h-69z" fill="#545454" />
                                <path transform="translate(452,522)" d="m0 0h69v34h-69z" fill="#545454" />
                                <path transform="translate(326,663)" d="m0 0h34v68h-34z" fill="#545454" />
                                <path transform="translate(291,663)" d="m0 0h34v68h-34z" fill="#545454" />
                                <path transform="translate(326,559)" d="m0 0h34v68h-34z" fill="#545454" />
                                <path transform="translate(291,559)" d="m0 0h34v68h-34z" fill="#545454" />
                                <path transform="translate(548,182)" d="m0 0h68v34h-68z" fill="#FF3131" />
                                <path transform="translate(548,120)" d="m0 0h68v34h-68z" fill="#FF3131" />
                                <path transform="translate(923,904)" d="m0 0h69v33h-69z" fill="#545454" />
                                <path transform="translate(175,767)" d="m0 0h69v33h-69z" fill="#545454" />
                                <path transform="translate(59,767)" d="m0 0h69v33h-69z" fill="#545454" />
                                <path transform="translate(452,661)" d="m0 0h69v33h-69z" fill="#545454" />
                                <path transform="translate(548,244)" d="m0 0h68v33h-68z" fill="#FF3131" />
                                <path transform="translate(548,59)" d="m0 0h68v33h-68z" fill="#FF3131" />
                                <path transform="translate(343,28)" d="m0 0h312v7l-115 1h-157l-40-1z" fill="#EAEAEA" />
                                <path transform="translate(844,938)" d="m0 0h34v34h-34z" fill="#545454" />
                                <path transform="translate(809,938)" d="m0 0h34v34h-34z" fill="#545454" />
                                <path transform="translate(716,938)" d="m0 0h34v34h-34z" fill="#545454" />
                                <path transform="translate(681,938)" d="m0 0h34v34h-34z" fill="#545454" />
                                <path transform="translate(326,732)" d="m0 0h34v34h-34z" fill="#545454" />
                                <path transform="translate(291,732)" d="m0 0h34v34h-34z" fill="#545454" />
                                <path transform="translate(326,628)" d="m0 0h34v34h-34z" fill="#545454" />
                                <path transform="translate(291,628)" d="m0 0h34v34h-34z" fill="#545454" />
                                <path transform="translate(326,524)" d="m0 0h34v34h-34z" fill="#545454" />
                                <path transform="translate(291,524)" d="m0 0h34v34h-34z" fill="#545454" />
                                <path transform="translate(513,182)" d="m0 0h34v34h-34z" fill="#FF3131" />
                                <path transform="translate(513,120)" d="m0 0h34v34h-34z" fill="#FF3131" />
                                <path transform="translate(844,904)" d="m0 0h34v33h-34z" fill="#545454" />
                                <path transform="translate(809,904)" d="m0 0h34v33h-34z" fill="#545454" />
                                <path transform="translate(716,904)" d="m0 0h34v33h-34z" fill="#545454" />
                                <path transform="translate(681,904)" d="m0 0h34v33h-34z" fill="#545454" />
                                <path transform="translate(326,767)" d="m0 0h34v33h-34z" fill="#545454" />
                                <path transform="translate(291,767)" d="m0 0h34v33h-34z" fill="#545454" />
                                <path transform="translate(513,244)" d="m0 0h34v33h-34z" fill="#FF3131" />
                                <path transform="translate(478,244)" d="m0 0h34v33h-34z" fill="#FF3131" />
                                <path transform="translate(443,244)" d="m0 0h34v33h-34z" fill="#FF3131" />
                                <path transform="translate(408,244)" d="m0 0h34v33h-34z" fill="#FF3131" />
                                <path transform="translate(513,59)" d="m0 0h34v33h-34z" fill="#FF3131" />
                                <path transform="translate(478,59)" d="m0 0h34v33h-34z" fill="#FF3131" />
                                <path transform="translate(443,59)" d="m0 0h34v33h-34z" fill="#FF3131" />
                                <path transform="translate(408,59)" d="m0 0h34v33h-34z" fill="#FF3131" />
                                <path transform="translate(325,524)" d="m0 0h1v34h34v-34h1v277h-70v-1h34v-33h-34v-1h34v-34h-34v-1h34v-68h-34v-1h34v-34h-34v-1h34v-68h-34v-1h34zm1 35v68h34v-68zm0 69v34h34v-34zm0 35v68h34v-68zm0 69v34h34v-34zm0 35v33h34v-33z" fill="#D4D4D4" />
                                <path transform="translate(1024,1025)" d="m0 0h19v25l-4 5-2 1h-13z" fill="#EAEAEA" />
                                <path transform="translate(680,903)" d="m0 0h70l1 1v68l-1 1h-69l-1-1zm1 1v33h34v-33zm35 0v33h34v-33zm-35 34v34h34v-34zm35 0v34h34v-34z" fill="#D4D4D4" />
                                <path transform="translate(809,903)" d="m0 0h70v69l-1 1h-69v-1h34v-34h-34v-1h34v-33h-34zm35 1v33h34v-33zm0 34v34h34v-34z" fill="#D4D4D4" />
                            </svg> -->


                        </div>

                        <!-- <table class="table table-striped table-borderless table-hover custom-table light">
                            <tbody>
                                <tr>
                                    <td><strong>Market</strong></td>
                                    <td><strong>Section</strong></td>
                                    <td><strong>Stall No.</strong></td>
                                    <td><strong>Stall Size</strong></td>
                                    <td><strong>Rental Fee</strong></td>
                                </tr>
                                <tr>
                                    <td id="market"></td>
                                    <td id="section"></td>
                                    <td id="stall_number"></td>
                                    <td id="stall_size"></td>
                                    <td id="rental_fee"></td>
                                </tr>
                            </tbody>
                        </table> -->
                    </div>
                </div>

                <!-- <div class="col-md-4 text-center">

                </div> -->

                <!-- Profile Info and Stalls -->
                <!-- <div class="col-md-8 px-5 divide">
                    <div id="map"></div>

                </div> -->
            </div>
        </div>
    </div>


    <?php include '../../includes/footer.php'; ?>
    <?php include '../../includes/theme.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        window.onload = function() {
            fetch('../actions/get_market.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    locationsData = data; // Store data globally

                    let marketLocationSelect = document.getElementById('market');
                    data.forEach(location => {
                        let option = document.createElement('option');
                        option.value = location.id;
                        option.text = location.market_name;
                        marketLocationSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching market locations:', error);
                    alert('Failed to load market locations. Please try again later.');
                });

        }

        // Add event listener to the market select element
        document.getElementById('market').addEventListener('change', function() {
            loadMarketInfo(this);
        });



        // Initialize the map and set its view
        const map = L.map('map').setView([14.676, 121.043], 15); // Set initial center and zoom level
        // Load and display tile layers (from OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        // Add a marker to the map
        L.marker([14.676, 121.043]).addTo(map)

        function showStallMap() {
            document.getElementById("map_section").style.display = "block";
        }


        function loadMarketInfo(marketSelect) {
            document.getElementById('viewStallsBtn').removeAttribute('disabled');

            const selectedOption = marketSelect.options[marketSelect.selectedIndex];
            const selectedId = selectedOption.value;

            // Fetch location data if it exists
            const selectedLocation = locationsData?.find(location => location.id == selectedId);
            document.getElementById('market_address').innerText = selectedLocation ? selectedLocation.market_address : 'No address available';

            // Send selectedId to the server using fetch
            fetch('../actions/map_action.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: selectedId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Update the stall count if available, or fallback
                    console.log(data);
                    document.getElementById('stall_count').textContent = data?.s_count ?? 'N/A';
                    document.getElementById('stall_vacant').textContent = data?.s_vacant ?? 'N/A';
                    document.getElementById('stall_occupied').textContent = data?.s_occupied ?? 'N/A';

                    document.getElementById('responseContainer').innerText = data.message || '';
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
    <script>
        // Select all stalls and add click event listeners
        document.querySelectorAll('.stall').forEach(stall => {
            stall.addEventListener('click', function() {
                const stallId = this.getAttribute('data-stall-id');

                // Set modal content
                // document.getElementById('modal-stall-title').textContent = `Stall: ${stallId}`;
                // document.getElementById('modal-stall-content').textContent = `Details about ${stallId}. (You can add more specific information here.)`;

                // Show the modal
                const stallModal = new bootstrap.Modal(document.getElementById('stallModal'));
                stallModal.show();
            });
        });
    </script>
</body>

</html>