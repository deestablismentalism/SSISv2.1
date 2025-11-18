document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('test-form');
    const submitBtn = document.getElementById('submit-btn');
    const resetBtn = document.getElementById('reset-btn');
    const loadingOverlay = document.getElementById('loading-overlay');
    const resultsSection = document.getElementById('results-section');
    const resultsContent = document.getElementById('results-content');
    const closeResultsBtn = document.getElementById('close-results');
    
    // Image preview functionality
    const frontInput = document.getElementById('report_card_front');
    const backInput = document.getElementById('report_card_back');
    const previewFront = document.getElementById('preview_front');
    const previewBack = document.getElementById('preview_back');
    
    frontInput.addEventListener('change', function(e) {
        previewImage(e.target.files[0], previewFront);
    });
    
    backInput.addEventListener('change', function(e) {
        previewImage(e.target.files[0], previewBack);
    });
    
    function previewImage(file, container) {
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                container.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        }
    }
    
    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Validate form
        if (!form.checkValidity()) {
            alert('Please fill in all required fields correctly.');
            return;
        }
        
        // Show loading
        loadingOverlay.style.display = 'flex';
        submitBtn.disabled = true;
        
        // Prepare form data
        const formData = new FormData();
        formData.append('student_name', document.getElementById('student_name').value);
        formData.append('student_lrn', document.getElementById('student_lrn').value);
        formData.append('report_card_front', frontInput.files[0]);
        formData.append('report_card_back', backInput.files[0]);
        
        const enrolleeId = document.getElementById('enrollee_id').value;
        if (enrolleeId) {
            formData.append('enrollee_id', enrolleeId);
        }
        
        try {
            const response = await fetch('/BackEnd/api/admin/postReportCardUpload.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            // Hide loading
            loadingOverlay.style.display = 'none';
            submitBtn.disabled = false;
            
            // Display results
            displayResults(result);
            
        } catch (error) {
            loadingOverlay.style.display = 'none';
            submitBtn.disabled = false;
            
            resultsContent.innerHTML = `
                <div class="error-box">
                    <h3>Error</h3>
                    <p><strong>Message:</strong> ${error.message}</p>
                    <p>Failed to connect to the API. Check console for details.</p>
                </div>
            `;
            resultsSection.style.display = 'block';
            console.error('Upload error:', error);
        }
    });
    
    function displayResults(result) {
        resultsContent.innerHTML = '';
        
        if (result.success) {
            const data = result.data || {};
            const status = data.status || 'unknown';
            const submissionId = data.submission_id || 'N/A';
            const flagReason = data.flag_reason || null;
            const ocrResult = data.ocr_result || null;
            
            let html = `
                <div class="success-box">
                    <h3>✓ Upload Successful</h3>
                    <p>${result.message}</p>
                </div>
                
                <div class="result-item">
                    <strong>Submission ID:</strong> ${submissionId}
                </div>
                
                <div class="result-item">
                    <strong>Verification Status:</strong> 
                    <span class="status-badge ${status === 'approved' ? 'status-approved' : 'status-flagged'}">
                        ${status.toUpperCase().replace('_', ' ')}
                    </span>
                </div>
            `;
            
            if (flagReason) {
                html += `
                    <div class="flag-reason-box">
                        <h3>⚠ Flag Reasons</h3>
                        <p>${escapeHtml(flagReason)}</p>
                    </div>
                `;
            }
            
            if (ocrResult) {
                html += generateOcrDetails(ocrResult);
            }
            
            resultsContent.innerHTML = html;
        } else {
            resultsContent.innerHTML = `
                <div class="error-box">
                    <h3>✗ Upload Failed</h3>
                    <p><strong>Message:</strong> ${escapeHtml(result.message)}</p>
                    <p><strong>HTTP Code:</strong> ${result.httpcode || 'N/A'}</p>
                </div>
            `;
        }
        
        resultsSection.style.display = 'block';
        resultsSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    
    function generateOcrDetails(ocrResult) {
        let html = `
            <div class="ocr-details">
                <h3>📄 OCR Analysis Results</h3>
                
                <div class="result-item">
                    <strong>LRN Detected:</strong> ${ocrResult.lrn || 'Not found'}
                    ${ocrResult.lrn_source ? ` <span style="color: #666;">(from ${ocrResult.lrn_source} side)</span>` : ''}
                </div>
                
                <div class="result-item">
                    <strong>Total Grades Found:</strong> ${ocrResult.grades_found || 0}
                    ${ocrResult.grades_primary_source ? ` <span style="color: #666;">(primarily from ${ocrResult.grades_primary_source} side)</span>` : ''}
                </div>
                
                <div class="result-item">
                    <strong>Total Word Count:</strong> ${ocrResult.word_count || 0}
                </div>
        `;
        
        if (ocrResult.flags && ocrResult.flags.length > 0) {
            html += `
                <div class="result-item">
                    <strong>OCR Flags:</strong>
                    <ul style="margin: 10px 0 0 20px;">
                        ${ocrResult.flags.map(flag => `<li>${escapeHtml(flag)}</li>`).join('')}
                    </ul>
                </div>
            `;
        }
        
        if (ocrResult.front_ocr) {
            html += `
                <div class="ocr-side">
                    <strong>Front Side (Student Info):</strong><br>
                    LRN: ${ocrResult.front_ocr.lrn || 'Not found'}<br>
                    Grades: ${ocrResult.front_ocr.grades_found || 0}<br>
                    Words: ${ocrResult.front_ocr.word_count || 0}
                    ${ocrResult.front_ocr.flags && ocrResult.front_ocr.flags.length > 0 ? 
                        `<br>Flags: ${ocrResult.front_ocr.flags.join(', ')}` : ''}
                </div>
            `;
        }
        
        if (ocrResult.back_ocr) {
            html += `
                <div class="ocr-side">
                    <strong>Back Side (Grades):</strong><br>
                    LRN: ${ocrResult.back_ocr.lrn || 'Not found'}<br>
                    Grades: ${ocrResult.back_ocr.grades_found || 0}<br>
                    Words: ${ocrResult.back_ocr.word_count || 0}
                    ${ocrResult.back_ocr.flags && ocrResult.back_ocr.flags.length > 0 ? 
                        `<br>Flags: ${ocrResult.back_ocr.flags.join(', ')}` : ''}
                </div>
            `;
        }
        
        html += `
                <details style="margin-top: 15px;">
                    <summary style="cursor: pointer; font-weight: 600; color: #004085;">View Raw JSON</summary>
                    <pre>${JSON.stringify(ocrResult, null, 2)}</pre>
                </details>
            </div>
        `;
        
        return html;
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    closeResultsBtn.addEventListener('click', function() {
        resultsSection.style.display = 'none';
    });
    
    resetBtn.addEventListener('click', function() {
        previewFront.innerHTML = '';
        previewBack.innerHTML = '';
        resultsSection.style.display = 'none';
    });
});
