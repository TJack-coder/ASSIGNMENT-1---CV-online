<?php
$cv = $cv ?? [];
$old = $old ?? [];
$errors = $errors ?? [];
$educations = $educations ?? [];
$workHistories = $workHistories ?? [];
$certificatesOfCv = $certificatesOfCv ?? [];
$skillsOfCv = $skillsOfCv ?? [];
$h = function($v){ return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); };
$val = function($key, $fallback = '') use ($old, $cv) { return $old[$key] ?? $cv[$key] ?? $fallback; };
$pick = function($row, $keys, $default = '') {
    if(!is_array($row)) return $default;
    foreach((array)$keys as $key){
        if(isset($row[$key]) && $row[$key] !== '') return $row[$key];
    }
    return $default;
};
$lookup = function($rows) use ($pick) {
    return array_values(array_map(function($row) use ($pick) {
        return [
            'id' => $pick($row, ['id','cv_id','user_id','cv_category_id','category_id','categories_id','country_id','countries_id','city_id','cities_id','province_id','provinces_id','district_id','districts_id','institution_id','institutions_id','degree_id','degree_level_id','degrees_id','major_id','majors_id','job_title_id','job_titles_id','employment_type_id','employment_types_id','industry_id','industries_id','skill_id','skills_id','certificate_name_id','certificate_names_id','certificate_id','organization_id','organizations_id','issuing_organization_id','proficiency_id','proficiency_level_id','proficients_id'], ''),
            'name' => $pick($row, ['name','title','full_name','category_name','skill_name','level_name','degree_name','city_name','province_name','country_name','district_name','major_name','industry_name','institution_name','job_title_name','employment_type_name','organization_name','certificate_name','proficiency_name'], '')
        ];
    }, is_array($rows) ? $rows : []));
};
$options = function($rows, $selected = null, $placeholder = '-- Select --') use ($h, $pick) {
    echo '<option value="">'.$h($placeholder).'</option>';
    foreach(is_array($rows) ? $rows : [] as $row){
        $id = $pick($row, ['id','cv_id','user_id','cv_category_id','category_id','categories_id','country_id','countries_id','city_id','cities_id','province_id','provinces_id','district_id','districts_id','institution_id','institutions_id','degree_id','degree_level_id','degrees_id','major_id','majors_id','job_title_id','job_titles_id','employment_type_id','employment_types_id','industry_id','industries_id','skill_id','skills_id','certificate_name_id','certificate_names_id','certificate_id','organization_id','organizations_id','issuing_organization_id','proficiency_id','proficiency_level_id','proficients_id'], '');
        $name = $pick($row, ['name','title','full_name','category_name','skill_name','level_name','degree_name','city_name','province_name','country_name','district_name','major_name','industry_name','institution_name','job_title_name','employment_type_name','organization_name','certificate_name','proficiency_name'], $id);
        echo '<option value="'.$h($id).'"'.(((string)$id === (string)$selected) ? ' selected' : '').'>'.$h($name).'</option>';
    }
};
$lookups = [
    'institutions' => $lookup($institutions ?? []),
    'degrees' => $lookup($degrees ?? []),
    'majors' => $lookup($majors ?? []),
    'jobTitles' => $lookup($jobTitles ?? []),
    'employmentTypes' => $lookup($employmentTypes ?? []),
    'industries' => $lookup($industries ?? []),
    'certificates' => $lookup($certificates ?? []),
    'organizations' => $lookup($organizations ?? []),
    'skills' => $lookup($skillsList ?? []),
    'proficiencies' => $lookup($proficiencies ?? []),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Job Seeker CV Builder</title>
<style>
:root{
    --bg:#edf4ff;--ink:#0f172a;--muted:#64748b;--line:#dbe7f3;--panel:#ffffff;
    --blue:#2563eb;--cyan:#06b6d4;--green:#16a34a;--amber:#f59e0b;--rose:#ef4444;
    --soft:#f8fbff;--shadow:0 24px 80px rgba(15,23,42,.12);
}
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;font-family:Inter,'Segoe UI',Arial,sans-serif;background:
    radial-gradient(circle at 8% 5%,rgba(37,99,235,.20),transparent 28%),
    radial-gradient(circle at 90% 8%,rgba(6,182,212,.18),transparent 28%),
    linear-gradient(135deg,#f8fbff,#eef4ff 48%,#f6fbff);color:var(--ink)}
a{text-decoration:none;color:inherit}.app{width:min(1440px,calc(100% - 36px));margin:28px auto 56px}.topbar{display:flex;justify-content:space-between;gap:20px;align-items:center;margin-bottom:22px}.brand{display:flex;align-items:center;gap:14px}.logo{width:50px;height:50px;border-radius:18px;background:linear-gradient(135deg,#2563eb,#06b6d4);display:grid;place-items:center;color:white;font-size:24px;box-shadow:0 18px 45px rgba(37,99,235,.25)}.brand h1{margin:0;font-size:clamp(26px,4vw,44px);letter-spacing:-1.5px}.brand p{margin:6px 0 0;color:var(--muted);font-size:14px}.top-actions{display:flex;gap:10px}.pill{display:inline-flex;align-items:center;justify-content:center;gap:8px;border-radius:999px;padding:12px 16px;font-weight:800;border:1px solid rgba(37,99,235,.2);background:rgba(255,255,255,.78);backdrop-filter:blur(12px);color:#1d4ed8}.pill.dark{background:#0f172a;color:#fff;border-color:#0f172a}.layout{display:grid;grid-template-columns:285px 1fr;gap:24px}.side{position:sticky;top:22px;align-self:start;background:rgba(255,255,255,.84);backdrop-filter:blur(18px);border:1px solid rgba(219,231,243,.95);border-radius:30px;box-shadow:var(--shadow);padding:22px}.side-title{font-weight:950;font-size:15px;margin-bottom:16px}.steps{display:grid;gap:10px}.step{display:flex;align-items:center;gap:11px;padding:12px;border-radius:18px;color:#334155;border:1px solid transparent}.step:hover{background:#eff6ff;border-color:#bfdbfe}.step-num{width:30px;height:30px;border-radius:12px;background:#eff6ff;color:#1d4ed8;display:grid;place-items:center;font-weight:950}.side-note{margin-top:20px;padding:16px;border-radius:20px;background:linear-gradient(135deg,#ecfeff,#eff6ff);border:1px solid #bfdbfe;color:#334155;font-size:13px;line-height:1.65}.form-card{background:rgba(255,255,255,.91);backdrop-filter:blur(16px);border:1px solid rgba(219,231,243,.95);border-radius:34px;box-shadow:var(--shadow);overflow:hidden}.hero{position:relative;overflow:hidden;padding:34px 38px;background:linear-gradient(135deg,#0f172a,#1e3a8a 55%,#0891b2);color:white}.hero:before{content:"";position:absolute;right:-90px;top:-90px;width:260px;height:260px;border-radius:50%;background:rgba(255,255,255,.11)}.hero:after{content:"";position:absolute;right:130px;bottom:-70px;width:160px;height:160px;border-radius:45px;transform:rotate(28deg);background:rgba(255,255,255,.08)}.hero-content{position:relative;z-index:1;display:flex;justify-content:space-between;gap:28px;align-items:flex-end}.hero h2{font-size:clamp(28px,4vw,46px);letter-spacing:-1.6px;margin:0}.hero p{max-width:760px;color:rgba(255,255,255,.82);line-height:1.7;margin:12px 0 0}.hero-badges{display:flex;flex-wrap:wrap;gap:10px;justify-content:flex-end}.hero-badge{white-space:nowrap;border:1px solid rgba(255,255,255,.24);background:rgba(255,255,255,.13);padding:9px 12px;border-radius:999px;font-weight:800;font-size:13px}.alert{margin:22px 38px 0;padding:16px 18px;border-radius:18px}.alert.error{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}.alert.success{background:#ecfdf5;color:#065f46;border:1px solid #bbf7d0}.section{padding:34px 38px;border-bottom:1px solid var(--line);background:linear-gradient(180deg,#fff,#fbfdff)}.section.alt{background:#f8fbff}.section:last-of-type{border-bottom:0}.section-head{display:flex;justify-content:space-between;gap:20px;align-items:flex-start;margin-bottom:24px}.section-kicker{display:inline-flex;align-items:center;gap:8px;color:#1d4ed8;font-size:12px;font-weight:950;text-transform:uppercase;letter-spacing:.7px;margin-bottom:8px}.section h3{font-size:26px;letter-spacing:-.8px;margin:0}.section p{color:var(--muted);line-height:1.65;margin:8px 0 0}.badge{display:inline-flex;align-items:center;justify-content:center;border-radius:999px;background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;font-weight:950;font-size:12px;padding:9px 12px;white-space:nowrap}.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px}.grid-3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}.full{grid-column:1/-1}.field label{display:flex;align-items:center;justify-content:space-between;margin-bottom:9px;font-size:13px;font-weight:900;color:#334155}.field small{font-weight:700;color:#94a3b8}input,select,textarea{width:100%;padding:15px 16px;border:1px solid #cbd5e1;border-radius:17px;background:white;color:#0f172a;font-size:15px;transition:.18s ease;box-shadow:0 1px 0 rgba(15,23,42,.02)}input:hover,select:hover,textarea:hover{border-color:#94a3b8}input:focus,select:focus,textarea:focus{outline:none;border-color:var(--blue);box-shadow:0 0 0 5px rgba(37,99,235,.12)}textarea{min-height:118px;resize:vertical}.dynamic-list{display:grid;gap:16px}.mini-card{position:relative;border:1px solid #dbe7f3;border-radius:24px;background:white;padding:22px;box-shadow:0 18px 45px rgba(15,23,42,.055)}.mini-card-title{display:flex;align-items:center;gap:10px;margin-bottom:16px;font-size:16px;font-weight:950}.mini-card-title span{width:32px;height:32px;border-radius:12px;background:#eff6ff;color:#1d4ed8;display:grid;place-items:center}.remove-btn{position:absolute;right:18px;top:18px;border:0;background:#fff1f2;color:#be123c;border-radius:999px;width:34px;height:34px;font-size:20px;font-weight:900;cursor:pointer}.remove-btn:hover{background:#ffe4e6}.add-btn{margin-top:18px;border:1px dashed #93c5fd;background:#eff6ff;color:#1d4ed8;border-radius:18px;padding:14px 18px;font-weight:950;cursor:pointer}.add-btn:hover{background:#dbeafe}.footer{position:sticky;bottom:0;z-index:5;display:flex;justify-content:space-between;align-items:center;gap:18px;padding:20px 38px;border-top:1px solid var(--line);background:rgba(255,255,255,.86);backdrop-filter:blur(18px)}.hint{color:var(--muted);font-size:13px;line-height:1.6}.primary{border:0;background:linear-gradient(135deg,#2563eb,#06b6d4);color:white;padding:15px 24px;border-radius:18px;font-size:15px;font-weight:950;cursor:pointer;box-shadow:0 20px 44px rgba(37,99,235,.24)}.secondary{display:inline-flex;color:#334155;background:white;border:1px solid var(--line);padding:15px 18px;border-radius:18px;font-weight:950}.btn-group{display:flex;gap:10px}.counter{font-weight:950;color:#1d4ed8}.empty-help{padding:16px;border-radius:18px;background:#fffbeb;color:#92400e;border:1px solid #fde68a;margin-top:16px;font-size:13px;line-height:1.6}@media(max-width:1120px){.layout{grid-template-columns:1fr}.side{position:relative;top:0}.steps{grid-template-columns:repeat(2,minmax(0,1fr))}.hero-content{display:block}.hero-badges{justify-content:flex-start;margin-top:18px}}@media(max-width:760px){.app{width:min(100% - 18px,1440px);margin-top:10px}.topbar,.footer,.section-head{display:block}.top-actions,.btn-group{margin-top:14px}.grid,.grid-3{grid-template-columns:1fr}.steps{grid-template-columns:1fr}.hero,.section,.footer{padding:26px 20px}.form-card,.side{border-radius:24px}.primary,.secondary{width:100%;justify-content:center}.footer{position:static}.alert{margin:18px 20px 0}}
</style>
</head>
<body>
<div class="app">
    <div class="topbar">
        <div class="brand">
            <div class="logo">CV</div>
            <div>
                <h1>Job Seeker CV Builder</h1>
                <p>Design a complete online CV with normalized, searchable data.</p>
            </div>
        </div>
        <div class="top-actions">
            <a class="pill" href="?route=seeker/cv/templates">Preview Templates</a>
            <a class="pill dark" href="?route=auth/logout">Logout</a>
        </div>
    </div>

    <div class="layout">
        <aside class="side">
            <div class="side-title">CV sections</div>
            <nav class="steps">
                <a class="step" href="#personal"><span class="step-num">A</span><strong>Personal</strong></a>
                <a class="step" href="#address"><span class="step-num">B</span><strong>Address</strong></a>
                <a class="step" href="#education"><span class="step-num">D</span><strong>Education</strong></a>
                <a class="step" href="#work"><span class="step-num">E</span><strong>Work</strong></a>
                <a class="step" href="#cert"><span class="step-num">F</span><strong>Certificates</strong></a>
                <a class="step" href="#skills"><span class="step-num">G</span><strong>Skills</strong></a>
            </nav>
        </aside>

        <main class="form-card">
            <div class="hero">
                <div class="hero-content">
                    <div>
                        <h2>Create / Update CV</h2>
                        <p>Fill in structured information, add multiple records, and save once. The same CV data can be rendered in Classic, Minimal, and Modern templates.</p>
                    </div>
                    <div class="hero-badges">
                        <span class="hero-badge">One CV per user</span>
                        <span class="hero-badge">Max 5 skills</span>
                        <span class="hero-badge">Search-ready</span>
                    </div>
                </div>
            </div>

            <?php if(!empty($errors)): ?><div class="alert error"><ul><?php foreach($errors as $error): ?><li><?= $h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
            <?php if(!empty($_SESSION['flash_success'])): ?><div class="alert success"><?= $h($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></div><?php endif; ?>

            <form method="post" action="?route=seeker/cv/store">
                <section class="section" id="personal">
                    <div class="section-head">
                        <div><div class="section-kicker">Required profile</div><h3>A. Personal Information</h3><p>Basic identity and professional summary of the candidate.</p></div>
                    </div>
                    <div class="grid">
                        <div class="field"><label>Full Name <small>required</small></label><input name="full_name" required value="<?= $h($val('full_name')) ?>" placeholder="Nguyen Van A"></div>
                        <div class="field"><label>Date of Birth <small>required</small></label><input type="date" name="date_of_birth" required value="<?= $h($val('date_of_birth', $val('birthday'))) ?>"></div>
                        <div class="field"><label>Gender</label><select name="gender"><option value="">-- Select gender --</option><?php foreach(['Male','Female','Other'] as $g): ?><option value="<?= $h($g) ?>" <?= (string)$val('gender')===$g?'selected':'' ?>><?= $h($g) ?></option><?php endforeach; ?></select></div>
                        <div class="field"><label>Email</label><input type="email" name="email" value="<?= $h($val('email')) ?>" placeholder="example@email.com"></div>
                        <div class="field"><label>Phone Number</label><input name="phone_number" value="<?= $h($val('phone_number', $val('phone'))) ?>" placeholder="09xxxxxxxx"></div>
                        <div class="field"><label>CV Category</label><select name="cv_category_id"><?php $options($categories ?? [], $val('cv_category_id', $val('category_id', $val('categories_id'))), '-- Select category --'); ?></select></div>
                        <div class="field full"><label>Summary</label><textarea name="summary" placeholder="Write a short summary about your strengths, goals, and experience."><?= $h($val('summary')) ?></textarea></div>
                    </div>
                </section>

                <section class="section alt" id="address">
                    <div class="section-head">
                        <div><div class="section-kicker">Structured fields</div><h3>B. Structured Contact Address</h3><p>Address is separated into queryable fields to support employer filtering.</p></div>
                        <span class="badge">No plain-text address</span>
                    </div>
                    <div class="grid">
                        <div class="field"><label>Country</label><select name="country_id"><?php $options($countries ?? [], $val('country_id', $val('countries_id')), '-- Select country --'); ?></select></div>
                        <div class="field"><label>City / Province</label><select name="city_id"><?php $options($cities ?? [], $val('city_id', $val('cities_id', $val('province_id'))), '-- Select city --'); ?></select></div>
                        <div class="field"><label>District</label><select name="district_id"><?php $options($districts ?? [], $val('district_id', $val('districts_id')), '-- Select district --'); ?></select></div>
                        <div class="field"><label>Postal Code</label><input name="postal_code" value="<?= $h($val('postal_code')) ?>" placeholder="700000"></div>
                        <div class="field full"><label>Street Address</label><input name="street_address" value="<?= $h($val('street_address', $val('address'))) ?>" placeholder="Street, ward, building..."></div>
                    </div>
                </section>

                <section class="section" id="education">
                    <div class="section-head"><div><div class="section-kicker">Dynamic records</div><h3>D. Education</h3><p>Add one or more education records using predefined degree and major data.</p></div></div>
                    <div id="educationRows" class="dynamic-list"></div>
                    <button class="add-btn" type="button" onclick="addEducation()">+ Add Degree</button>
                </section>

                <section class="section alt" id="work">
                    <div class="section-head"><div><div class="section-kicker">Dynamic records</div><h3>E. Work History</h3><p>Add work experience. Leave end year empty or type Present for current jobs.</p></div></div>
                    <div id="workRows" class="dynamic-list"></div>
                    <button class="add-btn" type="button" onclick="addWork()">+ Add Work History</button>
                </section>

                <section class="section" id="cert">
                    <div class="section-head"><div><div class="section-kicker">Dynamic records</div><h3>F. Certificates</h3><p>Add certificates and issuing organizations from lookup data.</p></div></div>
                    <div id="certRows" class="dynamic-list"></div>
                    <button class="add-btn" type="button" onclick="addCert()">+ Add Certificate</button>
                </section>

                <section class="section alt" id="skills">
                    <div class="section-head"><div><div class="section-kicker">Strongest skills only</div><h3>G. Skills</h3><p>Select a maximum of five strongest skills and proficiency levels.</p></div><span class="badge"><span id="skillCount">0</span>/5 selected</span></div>
                    <div id="skillRows" class="dynamic-list"></div>
                    <button class="add-btn" type="button" onclick="addSkill()">+ Add Skill</button>
                </section>

                <div class="footer">
                    <div class="hint">After saving, preview the same CV data with Classic, Minimal, or Modern layout.</div>
                    <div class="btn-group"><a class="secondary" href="?route=seeker/cv/templates">View Templates</a><button class="primary" type="submit">Save CV</button></div>
                </div>
            </form>
        </main>
    </div>
</div>

<script>
const LOOKUPS = <?= json_encode($lookups, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
const EXISTING = <?= json_encode(['educations'=>$educations,'work'=>$workHistories,'certs'=>$certificatesOfCv,'skills'=>$skillsOfCv], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
let edu=0, work=0, cert=0, skill=0;
function esc(v){return String(v ?? '').replace(/[&<>'"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c]));}
function opt(list, selected, placeholder){let html=`<option value="">${esc(placeholder)}</option>`;(list||[]).forEach(o=>{html += `<option value="${esc(o.id)}" ${String(o.id)===String(selected ?? '')?'selected':''}>${esc(o.name || o.id)}</option>`});return html;}
function removeCard(btn){btn.closest('.mini-card').remove(); updateSkillCount();}
function field(label, html){return `<div class="field"><label>${label}</label>${html}</div>`;}
function addEducation(row={}){const i=edu++; document.getElementById('educationRows').insertAdjacentHTML('beforeend', `<div class="mini-card"><button type="button" class="remove-btn" onclick="removeCard(this)">×</button><div class="mini-card-title"><span>${i+1}</span>Education record</div><div class="grid-3">${field('Institution',`<select name="educations[${i}][institution_id]">${opt(LOOKUPS.institutions,row.institution_id || row.institutions_id,'-- Institution --')}</select>`)}${field('Degree Level',`<select name="educations[${i}][degree_level_id]">${opt(LOOKUPS.degrees,row.degree_level_id || row.degree_id,'-- Degree --')}</select>`)}${field('Major',`<select name="educations[${i}][major_id]">${opt(LOOKUPS.majors,row.major_id,'-- Major --')}</select>`)}${field('Start Year',`<input name="educations[${i}][start_year]" value="${esc(row.start_year)}" placeholder="2022">`)}${field('End Year',`<input name="educations[${i}][end_year]" value="${esc(row.end_year)}" placeholder="2026">`)}${field('Description',`<input name="educations[${i}][description]" value="${esc(row.description)}" placeholder="Relevant coursework or achievement">`)}</div></div>`);}
function addWork(row={}){const i=work++; document.getElementById('workRows').insertAdjacentHTML('beforeend', `<div class="mini-card"><button type="button" class="remove-btn" onclick="removeCard(this)">×</button><div class="mini-card-title"><span>${i+1}</span>Work history record</div><div class="grid-3">${field('Company Name',`<input name="work_histories[${i}][company_name]" value="${esc(row.company_name)}" placeholder="Company name">`)}${field('Job Title',`<select name="work_histories[${i}][job_title_id]">${opt(LOOKUPS.jobTitles,row.job_title_id || row.job_titles_id,'-- Job title --')}</select>`)}${field('Employment Type',`<select name="work_histories[${i}][employment_type_id]">${opt(LOOKUPS.employmentTypes,row.employment_type_id || row.employment_types_id,'-- Type --')}</select>`)}${field('Industry',`<select name="work_histories[${i}][industry_id]">${opt(LOOKUPS.industries,row.industry_id || row.industries_id,'-- Industry --')}</select>`)}${field('Start Year',`<input name="work_histories[${i}][start_year]" value="${esc(row.start_year)}" placeholder="2023">`)}${field('End Year / Present',`<input name="work_histories[${i}][end_year]" value="${esc(row.end_year)}" placeholder="Present">`)}<div class="field full"><label>Job Description</label><textarea name="work_histories[${i}][job_description]" placeholder="Describe your responsibilities and achievements.">${esc(row.job_description || row.description)}</textarea></div></div></div>`);}
function addCert(row={}){const i=cert++; document.getElementById('certRows').insertAdjacentHTML('beforeend', `<div class="mini-card"><button type="button" class="remove-btn" onclick="removeCard(this)">×</button><div class="mini-card-title"><span>${i+1}</span>Certificate record</div><div class="grid-3">${field('Certificate Name',`<select name="certificates[${i}][certificate_name_id]">${opt(LOOKUPS.certificates,row.certificate_name_id || row.certificate_id,'-- Certificate --')}</select>`)}${field('Organization',`<select name="certificates[${i}][issuing_organization_id]">${opt(LOOKUPS.organizations,row.issuing_organization_id || row.organization_id || row.organizations_id,'-- Organization --')}</select>`)}${field('Year Issued',`<input name="certificates[${i}][year_issued]" value="${esc(row.year_issued)}" placeholder="2025">`)}<div class="field full"><label>Description</label><input name="certificates[${i}][description]" value="${esc(row.description)}" placeholder="Optional description"></div></div></div>`);}
function addSkill(row={}){if(document.querySelectorAll('#skillRows .mini-card').length>=5){alert('Maximum 5 skills only');return;}const i=skill++; document.getElementById('skillRows').insertAdjacentHTML('beforeend', `<div class="mini-card"><button type="button" class="remove-btn" onclick="removeCard(this)">×</button><div class="mini-card-title"><span>${i+1}</span>Strong skill</div><div class="grid">${field('Skill',`<select name="skills[${i}][skill_id]">${opt(LOOKUPS.skills,row.skill_id || row.skills_id,'-- Skill --')}</select>`)}${field('Proficiency',`<select name="skills[${i}][proficiency_level_id]">${opt(LOOKUPS.proficiencies,row.proficiency_level_id || row.proficiency_id || row.proficients_id,'-- Proficiency --')}</select>`)}</div></div>`); updateSkillCount();}
function updateSkillCount(){document.getElementById('skillCount').textContent = document.querySelectorAll('#skillRows .mini-card').length;}
(EXISTING.educations && EXISTING.educations.length ? EXISTING.educations : [{}]).forEach(addEducation);
(EXISTING.work && EXISTING.work.length ? EXISTING.work : [{}]).forEach(addWork);
(EXISTING.certs && EXISTING.certs.length ? EXISTING.certs : [{}]).forEach(addCert);
(EXISTING.skills && EXISTING.skills.length ? EXISTING.skills : [{}]).slice(0,5).forEach(addSkill);
updateSkillCount();
</script>
</body>
</html>
