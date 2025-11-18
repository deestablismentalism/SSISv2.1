document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('submissionModal');
    const modalContent = document.getElementById('modal-body');
    const closeBtn = document.querySelector('.close');
    
    // Close modal
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }
    
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // View submission details
    document.addEventListener('click', async function(e) {
        if (e.target.classList.contains('view-submission')) {
            const id = e.target.getAttribute('data-id');
            await loadSubmissionDetails(id);
        }
        
        if (e.target.classList.contains('approve-btn')) {
            const id = e.target.getAttribute('data-id');
            await updateStatus(id, 'approved');
        }
        
        if (e.target.classList.contains('reject-btn')) {
            const id = e.target.getAttribute('data-id');
            await updateStatus(id, 'reupload_needed');
        }
        
        if (e.target.classList.contains('reupload-btn')) {
            const id = e.target.getAttribute('data-id');
            await updateStatus(id, 'reupload_needed');
        }
    });
    
    async function loadSubmissionDetails(id) {
        try {
            const response = await fetch(`/BackEnd/api/admin/getReportCardSubmission.php?id=${id}`);
            const result = await response.json();
            
            if (result.success) {
                displaySubmissionDetails(result.data);
                modal.style.display = 'block';
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error loading submission:', error);
            alert('Error loading submission details');
        }
    }
    
    function displaySubmissionDetails(submission) {
        const ocrData = submission.ocr_json ? JSON.parse(submission.ocr_json) : null;
        
        let html = `
            <h3>Submission Details</h3>
            <div class="detail-row"><strong>ID:</strong> ${submission.id}</div>
            <div class="detail-row"><strong>Student Name:</strong> ${escapeHtml(submission.student_name)}</div>
            <div class="detail-row"><strong>LRN:</strong> ${escapeHtml(submission.student_lrn)}</div>
            <div class="detail-row"><strong>Status:</strong> ${getStatusBadge(submission.status)}</div>
            <div class="detail-row"><strong>Created At:</strong> ${formatDate(submission.created_at)}</div>
        `;
        
        if (submission.flag_reason) {
            html += `
                <div class="detail-row"><strong>Flag Reason:</strong></div>
                <div class="flag-reason-box" style="background: #fff3cd; border: 1px solid #ffc107; padding: 10px; margin: 10px 0; border-radius: 4px;">
                    ${escapeHtml(submission.flag_reason)}
                </div>
            `;
        }
        
        if (submission.report_card_front_path) {
            html += `
                <div class="detail-row"><strong>Report Card - Front:</strong></div>
                <div class="report-card-image">
                    <img src="../../${submission.report_card_front_path}" alt="Report Card Front" style="max-width: 100%; height: auto;">
                </div>
            `;
        }
        
        if (submission.report_card_back_path) {
            html += `
                <div class="detail-row"><strong>Report Card - Back:</strong></div>
                <div class="report-card-image">
                    <img src="../../${submission.report_card_back_path}" alt="Report Card Back" style="max-width: 100%; height: auto;">
                </div>
            `;
        }
        
        if (ocrData) {
            html += `
                <div class="detail-row"><strong>OCR Results (Combined):</strong></div>
                <div class="ocr-results">
                    <div><strong>LRN Found:</strong> ${ocrData.lrn || 'Not found'}</div>
                    <div><strong>Total Grades Found:</strong> ${ocrData.grades_found || 0}</div>
                    <div><strong>Total Word Count:</strong> ${ocrData.word_count || 0}</div>
                    ${ocrData.flags && ocrData.flags.length > 0 ? 
                        `<div><strong>Flags:</strong> ${ocrData.flags.join(', ')}</div>` : ''}
                    ${ocrData.front_ocr ? 
                        `<div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;"><strong>Front Side OCR (Student Info):</strong><br>
                        LRN: ${ocrData.front_ocr.lrn || 'Not found'}<br>
                        Grades: ${ocrData.front_ocr.grades_found || 0}, Words: ${ocrData.front_ocr.word_count || 0}</div>` : ''}
                    ${ocrData.back_ocr ? 
                        `<div style="margin-top: 10px;"><strong>Back Side OCR (Grades):</strong><br>
                        LRN: ${ocrData.back_ocr.lrn || 'Not found'}<br>
                        Grades: ${ocrData.back_ocr.grades_found || 0}, Words: ${ocrData.back_ocr.word_count || 0}</div>` : ''}
                    ${ocrData.lrn_source ? 
                        `<div style="margin-top: 10px; font-style: italic; color: #666;">LRN found on: ${ocrData.lrn_source.charAt(0).toUpperCase() + ocrData.lrn_source.slice(1)} side</div>` : ''}
                    ${ocrData.grades_primary_source ? 
                        `<div style="font-style: italic; color: #666;">Grades primarily from: ${ocrData.grades_primary_source.charAt(0).toUpperCase() + ocrData.grades_primary_source.slice(1)} side</div>` : ''}
                </div>
            `;
        }
        
        if (submission.status === 'flagged_for_review' || submission.status === 'pending_review') {
            html += `
                <div class="action-buttons">
                    <button class="approve-btn" data-id="${submission.id}">Approve</button>
                    <button class="reject-btn" data-id="${submission.id}">Reject</button>
                    <button class="reupload-btn" data-id="${submission.id}">Request Re-upload</button>
                </div>
            `;
        }
        
        modalContent.innerHTML = html;
    }
    
    async function updateStatus(id, status) {
        if (!confirm(`Are you sure you want to ${status === 'approved' ? 'approve' : status === 'reupload_needed' ? 'request re-upload for' : 'reject'} this submission?`)) {
            return;
        }
        
        try {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('status', status);
            
            const response = await fetch('/BackEnd/api/admin/postUpdateReportCardStatus.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert('Status updated successfully');
                location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error updating status:', error);
            alert('Error updating status');
        }
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function getStatusBadge(status) {
        const badges = {
            'approved': '<span style="background: #4CAF50; color: white; padding: 4px 8px; border-radius: 4px;">Approved</span>',
            'flagged_for_review': '<span style="background: #FF9800; color: white; padding: 4px 8px; border-radius: 4px;">Flagged</span>',
            'pending_review': '<span style="background: #2196F3; color: white; padding: 4px 8px; border-radius: 4px;">Pending</span>',
            'reupload_needed': '<span style="background: #F44336; color: white; padding: 4px 8px; border-radius: 4px;">Reupload Needed</span>'
        };
        return badges[status] || status;
    }
    
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString();
    }
});

