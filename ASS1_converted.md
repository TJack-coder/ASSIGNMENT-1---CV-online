### **Task Division Summary for 4 Members**

#### **Person A: Database & Admin Focus ( LIÊN KHANG)**

- Design/implement normalized DB schema (tables for CVs, addresses,
  education, etc.; reference tables for skills, categories, etc.).

- Populate reference data; enforce constraints (e.g., one CV per user).

- Build admin features: Manage users (CRUD), reference tables; remove
  invalid data.

- Assist with backend queries; handle admin validation.

- Rationale: Covers 25% database weight; foundational for others.

#### **Person B: Job Seeker & CV Management Focus ( MINH)**

- Implement seeker auth: Registration/login with RBAC.

- Build CV forms: Personal info, structured address, category, dynamic
  sections (education, work, certificates with "Add" buttons), skills
  (max 5, enforced).

- Use JS for dynamics; store normalized data.

- Test entry constraints; collaborate on user model.

- Rationale: Handles 30% CV completeness; core user input.

#### **Person C: Employer & Search Focus ( LONG)** 

- Implement **search:** Keywords, filters (category, location, skills,
  proficiency, degree) with AND logic.

- Add sorting (recent, alphabetical, experience length).

- Build CV views: Read-only, 3+ templates (e.g., Modern/Classic/Minimal)
  using same data; responsive UI.

- Integrate DB queries; test filters/sorting.

- Rationale: Covers 15% search + 10% UI/templates; employer usability.

#### **Person D: Architecture & Deliverables Focus**

- Set up MVC architecture; shared RBAC, server-side validation,
  responsive design.

- Handle integration: Consistent UI, dynamic JS code, end-to-end
  testing.

- Lead deliverables: Git repo (commits), ER diagram/SQL, technical
  report (decisions), demo video (~5 min).

- Oversee collaboration; document libs if used.

- Rationale: Addresses 15% code structure + 5% docs; ensures cohesion.

**Shared Duties**: Weekly syncs, testing, Git usage. Adjust based on
strengths; prioritize DB first to avoid bottlenecks. This balances
weights, covers all requirements (e.g., normalization, dynamics, no
free-text), and promotes learning outcomes.
