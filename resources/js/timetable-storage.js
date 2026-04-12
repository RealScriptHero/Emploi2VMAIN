/**
 * Global Timetable Storage and Advancement Calculator
 * 
 * This module manages:
 * - Storage of assigned modules in timetable (localStorage)
 * - Calculation of advancement based on assigned modules
 * - Automatic updates when modules are added/removed
 */

window.TimetableStorage = {
    // Storage keys
    STORAGE_KEY_GROUPS: 'app_timetable_groups',
    STORAGE_KEY_FORMATEURS: 'app_timetable_formateurs',
    STORAGE_KEY_MODULES: 'app_modules',
    
    /**
     * Get all assigned modules for a group
     * @param {number} groupId 
     * @returns {Array} Array of module codes
     */
    getGroupModules(groupId) {
        const data = JSON.parse(localStorage.getItem(this.STORAGE_KEY_GROUPS) || '{}');
        return data[groupId] || [];
    },
    
    /**
     * Get all assigned modules for a formateur
     * @param {number} formateurId 
     * @returns {Array} Array of module codes
     */
    getFormateurModules(formateurId) {
        const data = JSON.parse(localStorage.getItem(this.STORAGE_KEY_FORMATEURS) || '{}');
        return data[formateurId] || [];
    },
    
    /**
     * Add a module assignment for a group
     * @param {number} groupId 
     * @param {string} moduleCode 
     */
    addGroupModule(groupId, moduleCode) {
        const data = JSON.parse(localStorage.getItem(this.STORAGE_KEY_GROUPS) || '{}');
        if (!data[groupId]) data[groupId] = [];
        if (!data[groupId].includes(moduleCode)) {
            data[groupId].push(moduleCode);
            localStorage.setItem(this.STORAGE_KEY_GROUPS, JSON.stringify(data));
            window.dispatchEvent(new CustomEvent('timetable-updated', { detail: { type: 'group', id: groupId } }));
        }
    },
    
    /**
     * Add a module assignment for a formateur
     * @param {number} formateurId 
     * @param {string} moduleCode 
     */
    addFormateurModule(formateurId, moduleCode) {
        const data = JSON.parse(localStorage.getItem(this.STORAGE_KEY_FORMATEURS) || '{}');
        if (!data[formateurId]) data[formateurId] = [];
        if (!data[formateurId].includes(moduleCode)) {
            data[formateurId].push(moduleCode);
            localStorage.setItem(this.STORAGE_KEY_FORMATEURS, JSON.stringify(data));
            window.dispatchEvent(new CustomEvent('timetable-updated', { detail: { type: 'formateur', id: formateurId } }));
        }
    },
    
    /**
     * Remove a module assignment for a group
     * @param {number} groupId 
     * @param {string} moduleCode 
     */
    removeGroupModule(groupId, moduleCode) {
        const data = JSON.parse(localStorage.getItem(this.STORAGE_KEY_GROUPS) || '{}');
        if (data[groupId]) {
            data[groupId] = data[groupId].filter(m => m !== moduleCode);
            localStorage.setItem(this.STORAGE_KEY_GROUPS, JSON.stringify(data));
            window.dispatchEvent(new CustomEvent('timetable-updated', { detail: { type: 'group', id: groupId } }));
        }
    },
    
    /**
     * Remove a module assignment for a formateur
     * @param {number} formateurId 
     * @param {string} moduleCode 
     */
    removeFormateurModule(formateurId, moduleCode) {
        const data = JSON.parse(localStorage.getItem(this.STORAGE_KEY_FORMATEURS) || '{}');
        if (data[formateurId]) {
            data[formateurId] = data[formateurId].filter(m => m !== moduleCode);
            localStorage.setItem(this.STORAGE_KEY_FORMATEURS, JSON.stringify(data));
            window.dispatchEvent(new CustomEvent('timetable-updated', { detail: { type: 'formateur', id: formateurId } }));
        }
    },
    
    /**
     * Get all available modules from storage
     * @returns {Array} Array of module objects
     */
    getAllModules() {
        return JSON.parse(localStorage.getItem(this.STORAGE_KEY_MODULES) || '[]');
    },
    
    /**
     * Calculate advancement percentage for a group
     * Advancement = (assigned modules / total required modules) * 100
     * If no modules are required or assigned, return 0
     * 
     * @param {number} groupId 
     * @param {Array} requiredModules - Array of module codes that should be assigned
     * @returns {number} Percentage (0-100)
     */
    calculateGroupAdvancement(groupId, requiredModules = []) {
        const assignedModules = this.getGroupModules(groupId);
        
        // If no modules required, return 0
        if (!requiredModules || requiredModules.length === 0) {
            return assignedModules.length > 0 ? 0 : 0; // If assigned but no required, still 0
        }
        
        // Count unique assigned modules that are in required list
        const uniqueAssigned = [...new Set(assignedModules)];
        const completedModules = uniqueAssigned.filter(m => requiredModules.includes(m));
        
        // Calculate percentage
        const percentage = Math.round((completedModules.length / requiredModules.length) * 100);
        return Math.min(100, Math.max(0, percentage));
    },
    
    /**
     * Calculate advancement percentage for a formateur
     * Advancement = (assigned modules / total assigned modules) * 100
     * For formateurs, we consider modules they are assigned to teach
     * 
     * @param {number} formateurId 
     * @param {Array} assignedModules - Array of module codes the formateur should teach
     * @returns {number} Percentage (0-100)
     */
    calculateFormateurAdvancement(formateurId, assignedModules = []) {
        const timetableModules = this.getFormateurModules(formateurId);
        
        // If no modules assigned to teach, return 0
        if (!assignedModules || assignedModules.length === 0) {
            return timetableModules.length > 0 ? 0 : 0;
        }
        
        // Count unique timetable modules that match assigned modules
        const uniqueTimetable = [...new Set(timetableModules)];
        const completedModules = uniqueTimetable.filter(m => assignedModules.includes(m));
        
        // Calculate percentage: how many of their assigned modules are scheduled
        const percentage = Math.round((completedModules.length / assignedModules.length) * 100);
        return Math.min(100, Math.max(0, percentage));
    },
    
    /**
     * Initialize with sample data if storage is empty
     */
    init() {
        // Initialize groups timetable with sample data
        const groupsData = JSON.parse(localStorage.getItem(this.STORAGE_KEY_GROUPS) || '{}');
        if (Object.keys(groupsData).length === 0) {
            // Sample: Group 1 (DEV101) has modules M101-DEV, M102-RZX assigned
            groupsData[1] = ['M101-DEV', 'M102-RZX'];
            // Group 2 (NET202) has modules M201-BDD assigned
            groupsData[2] = ['M201-BDD'];
            localStorage.setItem(this.STORAGE_KEY_GROUPS, JSON.stringify(groupsData));
        }
        
        // Initialize formateurs timetable with sample data
        const formateursData = JSON.parse(localStorage.getItem(this.STORAGE_KEY_FORMATEURS) || '{}');
        if (Object.keys(formateursData).length === 0) {
            // Sample: Formateur 1 (Ahmed Benali) has modules M101-DEV, M102-RZX scheduled
            formateursData[1] = ['M101-DEV', 'M102-RZX'];
            // Formateur 2 (Fatima Alami) has module M201-BDD scheduled
            formateursData[2] = ['M201-BDD'];
            localStorage.setItem(this.STORAGE_KEY_FORMATEURS, JSON.stringify(formateursData));
        }
    }
};

// Initialize on load
if (typeof window !== 'undefined') {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => TimetableStorage.init());
    } else {
        TimetableStorage.init();
    }
}

