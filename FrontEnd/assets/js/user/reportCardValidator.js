// Report Card Pre-Validation Module
// Validates report cards BEFORE full enrollment submission

const ReportCardValidator = {
    validationEndpoint: '../../../BackEnd/api/user/validateReportCard.php',
    currentValidationStatus: null,
    
    /**
     * Validate report card images before allowing form submission
     * @param {FormData} formData - Must contain: student_name, student_lrn, report-card-front, report-card-back
     * @returns {Promise<{success: boolean, status: string, message: string, data: any}>}
     */
    async validateReportCard(formData) {
        try {
            const response = await fetch(this.validationEndpoint, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            this.currentValidationStatus = result.data?.status || null;
            
            return {
                success: result.success,
                status: result.data?.status || 'unknown',
                message: result.message || 'Validation complete',
                data: result.data || {}
            };
        }
        catch (error) {
            console.error('Report card validation error:', error);
            return {
                success: false,
                status: 'error',
                message: 'Network error during validation',
                data: {}
            };
        }
    },
    
    /**
     * Check if current validation allows submission
     * @returns {boolean}
     */
    canSubmit() {
        return this.currentValidationStatus === 'flagged_for_review' || 
               this.currentValidationStatus === 'approved';
    },
    
    /**
     * Reset validation state
     */
    reset() {
        this.currentValidationStatus = null;
    }
};

export default ReportCardValidator;
