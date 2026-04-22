

let skillCount = 0;
const MAX_SKILLS = 5;

// Add Education
function addEducation() {
    const template = document.getElementById('education-template');
    if (!template) return;
    const clone = template.content.cloneNode(true);
    document.getElementById('educationContainer').appendChild(clone);
}

// Add Work History
function addWorkHistory() {
    const template = document.getElementById('work-template');
    if (!template) return;
    const clone = template.content.cloneNode(true);
    document.getElementById('workContainer').appendChild(clone);
}

// Add Certificate
function addCertificate() {
    const template = document.getElementById('certificate-template');
    if (!template) return;
    const clone = template.content.cloneNode(true);
    document.getElementById('certificateContainer').appendChild(clone);
}

// Add Skill (max 5)
function addSkill() {
    if (skillCount >= MAX_SKILLS) {
        alert("❌ Maximum 5 strongest skills allowed!");
        return;
    }

    const template = document.getElementById('skill-template');
    if (!template) return;

    const clone = template.content.cloneNode(true);
    document.getElementById('skillsContainer').appendChild(clone);

    skillCount++;
    if (skillCount >= MAX_SKILLS) {
        document.getElementById('addSkillBtn').disabled = true;
    }
}

// Remove row (used by all dynamic sections)
function removeRow(btn) {
    btn.closest('.dynamic-row').remove();
    
    // If it's a skill row, decrease counter
    if (btn.closest('#skillsContainer')) {
        skillCount = Math.max(0, skillCount - 1);
        document.getElementById('addSkillBtn').disabled = skillCount >= MAX_SKILLS;
    }
}

// Make functions globally available
window.addEducation = addEducation;
window.addWorkHistory = addWorkHistory;
window.addCertificate = addCertificate;
window.addSkill = addSkill;
window.removeRow = removeRow;