<style>
                        /* Payslip layout */
                        .card > .card-body {
                            padding: 0;
                        }

                        /* Main page layout */
                        .payslip-left-sidebar {
                            margin: 0;
                            display: flex;
                            align-items: stretch;
                            gap: 0.1rem;
                        }

                        .payslip-shift-panel,
                        .payslip-table-panel {
                            min-width: 0;
                            padding: 0;

                        }
                        .payslip-table-panel {
                            flex: 1;
                            width: 100%;
                        }

                        .payslip-shift-panel > .border,
                        .payslip-table-panel > .border {
                            width: 100%;
                            height: 100%;
                            min-width: 0;
                        }

                        /* Left panel */
                        .left-panel .form-label {
                            font-size: .95rem;
                        }

                        .left-panel .btn-group > .btn {
                            flex: 1;
                            min-width: 0;
                        }

                        .left-panel .form-control {
                            border-radius: 6px;
                        }

                        #selectedShiftTypeFields .form-label {
                            font-size: .72rem;
                            margin-bottom: .15rem;
                            white-space: nowrap;
                        }

                        #selectedShiftTypeFields .form-control {
                            padding: .3rem .4rem;
                            font-size: .82rem;
                            min-width: 0;
                        }

                        #selectedShiftTypeFields .row {
                            margin-left: -.25rem;
                            margin-right: -.25rem;
                        }

                        #selectedShiftTypeFields .row > [class*="col-"] {
                            padding-left: .25rem;
                            padding-right: .25rem;
                        }

                        /* Shifts table */
                        .shifts-header {
                            min-height: 64px;
                        }

                        .table thead th {
                            background: #fafafa;
                        }

                        .table-container {
                            width: 100%;
                            height: 460px;
                            overflow: auto;
                        }

                        .table-container table {
                            min-width: 100%;

                            table-layout: fixed;
                        }

                        .table-container th:nth-child(1),
                        .table-container td:nth-child(1) { width: 10%; }  /* Shift Type */
                        .table-container th:nth-child(2),
                        .table-container td:nth-child(2) { width: 10%; }   /* Date */
                        .table-container th:nth-child(3),
                        .table-container td:nth-child(3) { width: 10%; }   /* Start */
                        .table-container th:nth-child(4),
                        .table-container td:nth-child(4) { width: 10%; }   /* End */
                        .table-container th:nth-child(5),
                        .table-container td:nth-child(5) { width: 9%; }   /* Breaks */
                        .table-container th:nth-child(6),
                        .table-container td:nth-child(6) { width: 9%; }   /* Rate */
                        .table-container th:nth-child(7),
                        .table-container td:nth-child(7) { width: 9%; }   /* Laundry */
                        .table-container th:nth-child(8),
                        .table-container td:nth-child(8) { width: 9%; }   /* Uniform */
                        .table-container th:nth-child(9),
                        .table-container td:nth-child(9) { width: 6%;

                        align-items: center;
                        justify-content: center;

                    }
                    .table-container td:nth-child(9) .form-check-input {
                        margin: 0;
                    }
                    /* Holiday */
                    .table-container th:nth-child(10),
                    .table-container td:nth-child(10) { width: 15%; } /* Operations */



                    /* Modals */
                    .modal .modal-body .form-label {
                        font-weight: 600;
                    }

                    .payslip-modal .modal-dialog {
                        width: min(100%, 720px);
                        max-width: calc(100vw - 2rem);
                    }

                    .payslip-modal .modal-dialog.modal-sm {
                        max-width: 420px;
                    }
                    .payslip-main-table {
                        height: 460px;
                        overflow: auto;
                    }

                    .payslip-modal .modal-content {
                        border: 0;
                        box-shadow: 0 12px 32px rgba(0, 0, 0, .15);
                    }

                    .payslip-modal .modal-header {
                        padding: .9rem 1rem;
                    }

                    .payslip-modal .modal-body {
                        padding: .9rem 1rem .5rem;
                    }

                    .payslip-modal .modal-footer {
                        display: flex;
                        gap: .5rem;
                        padding: .75rem 1rem 1rem;
                        border-top: 0;
                    }

                    .payslip-modal .modal-footer .btn {
                        flex: 1;
                    }

                    /* Shift form */
                    .shift-form-grid .row {
                        margin-bottom: .3rem;
                    }

                    .shift-form-grid .row > [class^="col"] {
                        display: flex;
                        flex-direction: column;
                    }

                    .shift-form-grid .form-label {
                        font-size: .82rem;
                        font-weight: 600;
                        margin-bottom: .2rem;
                    }

                    .shift-form-grid .form-control,
                    .shift-form-grid .form-select {
                        width: 100%;
                        min-height: 2.25rem;
                        padding: .35rem .55rem;
                        font-size: .92rem;
                    }

                    .shift-form-grid .three-col-row {
                        display: grid;
                        grid-template-columns: repeat(3, minmax(0, 1fr));
                        gap: .5rem;
                    }

                    .shift-form-grid .three-col-row > .col-sm-4 {
                        width: 100%;
                    }
                    .row.g-2.col-row {
                        width: 100%;


                    }
                    .shift-form-grid .col-row > div {
                        display: flex;
                        flex-direction: column;
                        justify-content: flex-start;
                    }

                    .shift-form-grid .col-row .form-label {
                        height: 1.25rem;
                        line-height: 1.25rem;
                        margin-bottom: .2rem;
                    }

                    .shift-form-grid .col-row .form-control,
                    .shift-form-grid .col-row .form-select {
                        width: 100%;
                        height: 2.25rem;
                        min-height: 2.25rem;
                        box-sizing: border-box;
                        margin: 0;
                    }
                    f
                    .shift-form-grid .col-row {
                        width: 100%;
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 0.5rem;
                        margin: 0 0 .3rem 0;
                    }

                    .shift-form-grid .col-row > div {
                        width: 100%;
                        min-width: 0;
                        padding: 0;
                    }

                    .shift-form-grid .col-row .form-control,
                    .shift-form-grid .col-row .form-select {
                        width: 100%;
                    }


                    .shift-form-grid .form-check,
                    .shift-form-grid .holiday-field {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        min-height: 2.25rem;
                        margin: 0;
                        text-align: center;
                    }

                    .shift-form-grid .form-check {
                        gap: .25rem;
                        padding-top: .35rem;
                    }

                    .shift-form-grid .form-check-input {
                        margin: 0;
                    }
                    .shift-form-grid .col-row {
                        width: 100%;
                        display: grid;
                        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
                        gap: .5rem;
                        margin: 0 0 .3rem 0;
                    }

                    .shift-form-grid .col-row > div {
                        width: 100%;
                        min-width: 0;
                        display: flex;
                        flex-direction: column;
                    }

                    .shift-form-grid .col-row .form-label {
                        height: 1.25rem;
                        line-height: 1.25rem;
                        margin-bottom: .2rem;
                    }

                    .shift-form-grid .col-row .form-control,
                    .shift-form-grid .col-row .form-select {
                        width: 100%;
                        height: 2.25rem;
                        min-height: 2.25rem;
                        box-sizing: border-box;
                        margin: 0;
                        padding: .35rem .55rem;
                        font-size: .92rem;
                        line-height: 1.5;
                    }
                </style>