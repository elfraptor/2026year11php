<script>
                                                (() => {
                                                    const shiftTypes = <?= json_encode($shift_type_rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
                                                    const select = document.getElementById('shiftTypeSelect');
                                                    const fields = document.getElementById('selectedShiftTypeFields');
                                                    const hiddenId = document.getElementById('shiftTypeId');

                                                    if (!select || !fields || !hiddenId) {
                                                            return;
                                                        }

                                                        const fieldNames = [
                                                            'name',
                                                            'rate',
                                                            'deductable',
                                                            'l_allow',
                                                            'u_allow',
                                                            'pm_allow',
                                                            'holi_loading',
                                                            'sat_loading',
                                                            'sun_loading',
                                                            'fringe',
                                                            'tax'
                                                        ];

                                                        const applyShiftType = () => {
                                                            const selectedId = select.value;
                                                            const selectedShiftType = shiftTypes.find((shiftType) => String(shiftType.id) === String(selectedId));

                                                            hiddenId.value = selectedShiftType ? selectedShiftType.id : '';

                                                            fieldNames.forEach((fieldName) => {
                                                                const input = fields.querySelector(`[name="${fieldName}"]`);

                                                                if (!input) {
                                                                        return;
                                                                    }

                                                                    input.value = selectedShiftType ? (selectedShiftType[fieldName] ?? '') : '';
                                                            });
                                                        };

                                                        select.addEventListener('change', applyShiftType);
                                                        applyShiftType();

                                                        document.querySelectorAll('.shift-record-type-select').forEach((recordSelect) => {
                                                            recordSelect.addEventListener('change', () => {
                                                                const selectedShiftType = shiftTypes.find((shiftType) => String(shiftType.id) === String(recordSelect.value));
                                                                const form = recordSelect.closest('form');

                                                                if (!form || !selectedShiftType) {
                                                                        return;
                                                                    }

                                                                    const fieldMap = {
                                                                        rate: 'rate',
                                                                        laundry: 'l_allow',
                                                                        uniform: 'u_allow',
                                                                    };

                                                                    Object.entries(fieldMap).forEach(([recordField, typeField]) => {
                                                                        const input = form.querySelector(`[name="${recordField}"]`);

                                                                        if (input && selectedShiftType[typeField] !== undefined) {
                                                                                input.value = selectedShiftType[typeField] ?? '';
                                                                            }
                                                                    });
                                                            });
                                                    });
                                            })();

                                            document.querySelectorAll('form').forEach((form) => {
                                                const startTimeInput = form.querySelector('.shift-start-time');
                                                const endTimeInput = form.querySelector('.shift-end-time');

                                                if (!startTimeInput || !endTimeInput) {
                                                        return;
                                                    }

                                                    const timeToMinutes = (time) => {
                                                        const [hours, minutes] = time.split(':').map(Number);
                                                        return (hours * 60) + minutes;
                                                    };

                                                    const minutesToTime = (minutes) => {
                                                        const normalizedMinutes = ((minutes % 1440) + 1440) % 1440;
                                                        const hours = String(Math.floor(normalizedMinutes / 60)).padStart(2, '0');
                                                        const remainingMinutes = String(normalizedMinutes % 60).padStart(2, '0');

                                                        return `${hours}:${remainingMinutes}`;
                                                    };

                                                    const getDuration = () => {
                                                        if (!startTimeInput.value || !endTimeInput.value) {
                                                                return null;
                                                            }

                                                            let duration = timeToMinutes(endTimeInput.value) - timeToMinutes(startTimeInput.value);

                                                            if (duration < 0) {
                                                                    duration += 1440;
                                                                }

                                                                return duration;
                                                            };

                                                            let duration = getDuration();

                                                            startTimeInput.addEventListener('change', () => {
                                                                if (duration !== null) {
                                                                        endTimeInput.value = minutesToTime(timeToMinutes(startTimeInput.value) + duration);
                                                                    }
                                                            });

                                                            endTimeInput.addEventListener('change', () => {
                                                                duration = getDuration();
                                                        });
                                                });
                                            </script>