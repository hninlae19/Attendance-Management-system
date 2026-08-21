<!-- ============ COMPANY RULES & POLICIES MANUAL ============ -->

<!-- Header Banner -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-8 mb-8 shadow-xl text-white" data-aos="fade-down">
    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <i class="fa-solid fa-book-bookmark"></i>
                    <span>Official Guide</span>
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/30 border border-emerald-400/40 text-emerald-100 text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Active Standard</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight font-outfit text-white">
                Company Rules & Policies <span class="text-yellow-300">Handbook</span>
            </h1>
            <p class="text-indigo-100 text-xs sm:text-sm mt-1 max-w-2xl">
                Comprehensive guide detailing official office hours, attendance thresholds, payroll deductions, leave policies, and overtime regulations.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/30 text-white text-xs font-bold transition-all flex items-center gap-2 backdrop-blur-md shadow-sm">
                <i class="fa-solid fa-print"></i>
                <span>Print Handbook</span>
            </button>
        </div>
    </div>
</div>

<div class="space-y-8" data-aos="fade-up" data-aos-delay="100">

    <!-- ========================================== -->
    <!-- SECTION 1: WORKING HOURS & ATTENDANCE      -->
    <!-- ========================================== -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 lg:p-8 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-100 dark:border-indigo-800 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white font-outfit">1. Office Schedule & Attendance Rules</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Core operational hours, daily requirements, and attendance status classifications.</p>
            </div>
        </div>

        <!-- Office Hours Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="p-4 rounded-2xl bg-indigo-50/50 dark:bg-slate-900/50 border border-indigo-100 dark:border-slate-700">
                <div class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-600 dark:text-sky-400 mb-1">Office Start Time</div>
                <div class="text-2xl font-black text-slate-900 dark:text-white font-outfit">9:00 AM</div>
                <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Check-in time window starts</div>
            </div>
            <div class="p-4 rounded-2xl bg-indigo-50/50 dark:bg-slate-900/50 border border-indigo-100 dark:border-slate-700">
                <div class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-600 dark:text-sky-400 mb-1">Office End Time</div>
                <div class="text-2xl font-black text-slate-900 dark:text-white font-outfit">5:00 PM</div>
                <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Standard shift concludes</div>
            </div>
            <div class="p-4 rounded-2xl bg-indigo-50/50 dark:bg-slate-900/50 border border-indigo-100 dark:border-slate-700">
                <div class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-600 dark:text-sky-400 mb-1">Required Working Hours</div>
                <div class="text-2xl font-black text-indigo-600 dark:text-indigo-400 font-outfit">8.0 Hours</div>
                <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Full shift daily requirement</div>
            </div>
        </div>

        <!-- Working Hours Formula -->
        <div class="mb-6 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-700">
            <div class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 flex items-center gap-2">
                <i class="fa-solid fa-calculator text-indigo-500"></i> Working Hours Calculation Formula
            </div>
            <div class="font-mono text-sm sm:text-base font-extrabold text-indigo-600 dark:text-sky-400 py-1">
                Working Hours = Check-Out Time − Check-In Time
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Working duration is calculated dynamically using precise check-in and check-out timestamps.</p>
        </div>

        <!-- Attendance Status Matrix Table -->
        <h3 class="text-xs font-extrabold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-3">Attendance Status Classifications</h3>
        <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-700 mb-6">
            <table class="w-full text-xs sm:text-sm text-left text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 uppercase font-bold text-[11px] border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="px-5 py-3.5">Status Badge</th>
                        <th class="px-5 py-3.5">Working Hours Threshold</th>
                        <th class="px-5 py-3.5">Condition & Classification</th>
                        <th class="px-5 py-3.5">Payroll Impact</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 font-medium">
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                <i class="fa-solid fa-check mr-1.5"></i> Present
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-mono font-bold text-slate-900 dark:text-white">≥ 7h 45m (7.75 hrs)</td>
                        <td class="px-5 py-3.5">Employee worked 7 hours and 45 minutes or more.</td>
                        <td class="px-5 py-3.5 text-emerald-600 dark:text-emerald-400 font-bold">No absence deduction</td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                <i class="fa-solid fa-hourglass-half mr-1.5"></i> Late
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-mono font-bold text-slate-900 dark:text-white">6h 00m to &lt; 7h 45m</td>
                        <td class="px-5 py-3.5">Working hours are between 6 hours and less than 7 hours 45 minutes.</td>
                        <td class="px-5 py-3.5 text-amber-600 dark:text-amber-400 font-bold">Late minute deduction applied</td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                <i class="fa-solid fa-circle-half-stroke mr-1.5"></i> Half Day
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-mono font-bold text-slate-900 dark:text-white">4h 00m to &lt; 6h 00m</td>
                        <td class="px-5 py-3.5">Working hours are between 4 hours and less than 6 hours.</td>
                        <td class="px-5 py-3.5 text-rose-600 dark:text-rose-400 font-bold">Deduct 0.5 × Daily Salary</td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-rose-100 text-rose-800 dark:bg-rose-900/60 dark:text-rose-200 border border-rose-300 dark:border-rose-700">
                                <i class="fa-solid fa-xmark mr-1.5"></i> Absent (Full Day)
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-mono font-bold text-slate-900 dark:text-white">&lt; 4h 00m or No Check-in</td>
                        <td class="px-5 py-3.5">Worked less than 4 hours, or did not check in on a working day.</td>
                        <td class="px-5 py-3.5 text-rose-700 dark:text-rose-300 font-bold">Deduct 1.0 × Daily Salary</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Late Arrival Rules -->
        <div class="p-5 rounded-2xl bg-amber-50/60 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/50 text-slate-800 dark:text-slate-200 text-xs sm:text-sm">
            <div class="font-extrabold text-amber-800 dark:text-amber-300 uppercase tracking-wider mb-1 flex items-center gap-2">
                <i class="fa-solid fa-user-clock text-amber-600"></i> Late Minutes Calculation Rule
            </div>
            <p class="text-slate-600 dark:text-slate-300 mt-1">
                Late minutes are calculated whenever an employee checks in after <strong>9:00:00 AM</strong>.
            </p>
            <div class="mt-2 font-mono font-bold text-amber-700 dark:text-amber-300 bg-white dark:bg-slate-900 p-2.5 rounded-xl border border-amber-200 dark:border-amber-800 inline-block">
                Late Minutes = Check-In Time − 09:00:00 AM (if Check-In &gt; 09:00:00)
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- SECTION 2: SALARY & DEDUCTION FORMULAS     -->
    <!-- ========================================== -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 lg:p-8 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white font-outfit">2. Payroll Rates & Attendance Deduction Formulas</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Exact formulas used by the system to compute daily rates, hourly wages, and payroll deductions.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Daily Salary -->
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700">
                <div class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-600 dark:text-sky-400 mb-1">A. Daily Salary Rate</div>
                <div class="font-mono text-sm sm:text-base font-extrabold text-slate-900 dark:text-white py-1">
                    Daily Salary = Basic Salary ÷ Working Days in Month
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Based on exact scheduled business working days (excluding weekends and public holidays).</p>
            </div>

            <!-- Hourly Rate -->
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700">
                <div class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-600 dark:text-sky-400 mb-1">B. Hourly Rate</div>
                <div class="font-mono text-sm sm:text-base font-extrabold text-slate-900 dark:text-white py-1">
                    Hourly Rate = Daily Salary ÷ 8 Hours
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Standard unit rate used for late minute deductions and hourly calculations.</p>
            </div>
        </div>

        <!-- Deduction Breakdown Table -->
        <h3 class="text-xs font-extrabold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-3">Itemized Deduction Formulas</h3>
        <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-700 mb-6">
            <table class="w-full text-xs sm:text-sm text-left text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 uppercase font-bold text-[11px] border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="px-5 py-3.5">Deduction Type</th>
                        <th class="px-5 py-3.5">Computation Formula</th>
                        <th class="px-5 py-3.5">Explanation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 font-medium">
                    <tr>
                        <td class="px-5 py-3.5 font-bold text-amber-600 dark:text-amber-400">Late Arrival Deduction</td>
                        <td class="px-5 py-3.5 font-mono font-bold text-slate-900 dark:text-white">Hourly Rate × (Late Minutes ÷ 60)</td>
                        <td class="px-5 py-3.5">Proportional deduction for late arrival time exceeding 9:00 AM.</td>
                    </tr>
                    <tr>
                        <td class="px-5 py-3.5 font-bold text-rose-600 dark:text-rose-400">Half-Day Absence</td>
                        <td class="px-5 py-3.5 font-mono font-bold text-slate-900 dark:text-white">Count × (Daily Salary × 0.5)</td>
                        <td class="px-5 py-3.5">Deducts half day of compensation for 4h to &lt;6h shifts.</td>
                    </tr>
                    <tr>
                        <td class="px-5 py-3.5 font-bold text-rose-700 dark:text-rose-300">Full-Day Absence</td>
                        <td class="px-5 py-3.5 font-mono font-bold text-slate-900 dark:text-white">Count × Daily Salary</td>
                        <td class="px-5 py-3.5">Full daily wage deducted for shifts &lt;4h or missed working days.</td>
                    </tr>
                    <tr class="bg-indigo-50/30 dark:bg-indigo-950/20 font-bold">
                        <td class="px-5 py-3.5 text-indigo-700 dark:text-sky-300">Total Attendance Deduction</td>
                        <td class="px-5 py-3.5 font-mono text-indigo-700 dark:text-sky-300">Late Ded + Half-Day Ded + Full-Day Ded</td>
                        <td class="px-5 py-3.5">Sum total deducted from monthly gross compensation.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- SECTION 3: LEAVE POLICIES & PROCEDURES     -->
    <!-- ========================================== -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 lg:p-8 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
            <div class="w-12 h-12 rounded-2xl bg-cyan-50 dark:bg-cyan-950/60 border border-cyan-100 dark:border-cyan-800 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-calendar-minus"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white font-outfit">3. Leave Policies & Time-Off Rules</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Rules governing leave requests, types of leave, approvals, and payroll exemptions.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700">
                <div class="text-xs font-extrabold uppercase tracking-wider text-indigo-600 dark:text-sky-400 mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i> Approved Leaves
                </div>
                <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-300">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-emerald-500 mt-0.5"></i>
                        <span>Approved leaves are <strong>exempt from absence deductions</strong>.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-emerald-500 mt-0.5"></i>
                        <span>Employees on approved leave are treated as authorized off-duty.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-emerald-500 mt-0.5"></i>
                        <span>Leave requests must be submitted in advance through the employee portal.</span>
                    </li>
                </ul>
            </div>

            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700">
                <div class="text-xs font-extrabold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-ban text-rose-500"></i> Restrictions & Overtime Rule
                </div>
                <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-300">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-xmark text-rose-500 mt-0.5"></i>
                        <span><strong>No Overtime on Leave</strong>: Employees cannot be assigned or perform overtime shifts while on approved leave.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-xmark text-rose-500 mt-0.5"></i>
                        <span>Unapproved time off is automatically marked as <strong>Full-Day Absence</strong>.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- SECTION 4: OVERTIME (OT) RULES & RATES     -->
    <!-- ========================================== -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 lg:p-8 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 border border-amber-100 dark:border-amber-800 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-business-time"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white font-outfit">4. Overtime (OT) Operations & Pay Rules</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Shift windows, multiplier rates, daily/monthly maximum limits, and compensation formulas.</p>
            </div>
        </div>

        <!-- Overtime Shift Windows -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Working Day OT -->
            <div class="p-5 rounded-2xl bg-gradient-to-br from-indigo-50 to-violet-50 dark:from-slate-900 dark:to-slate-800 border border-indigo-200 dark:border-slate-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 dark:text-sky-300">Regular Working Day OT</span>
                    <span class="px-2.5 py-1 rounded-md text-xs font-black bg-indigo-600 text-white shadow-sm font-mono">1.5× Rate</span>
                </div>
                <div class="text-2xl font-black text-slate-900 dark:text-white font-outfit">5:00 PM – 9:00 PM</div>
                <div class="text-xs text-slate-600 dark:text-slate-300 mt-2 space-y-1">
                    <p>• Only allowed after regular work hours (17:00 to 21:00).</p>
                    <p>• Requires employee to have checked in and attended work that day.</p>
                    <p>• Maximum of 4.0 hours per shift.</p>
                </div>
            </div>

            <!-- Weekend & Holiday OT -->
            <div class="p-5 rounded-2xl bg-gradient-to-br from-amber-50 to-orange-50 dark:from-slate-900 dark:to-slate-800 border border-amber-200 dark:border-slate-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-300">Weekend & Holiday OT</span>
                    <span class="px-2.5 py-1 rounded-md text-xs font-black bg-amber-600 text-white shadow-sm font-mono">2.0× / 3.0× Rate</span>
                </div>
                <div class="text-2xl font-black text-slate-900 dark:text-white font-outfit">9:00 AM – 5:00 PM</div>
                <div class="text-xs text-slate-600 dark:text-slate-300 mt-2 space-y-1">
                    <p>• Allowed during daytime schedule (09:00 to 17:00).</p>
                    <p>• <strong>Weekends (Sat/Sun)</strong>: Calculated at <strong>2.0× multiplier</strong>.</p>
                    <p>• <strong>Public Holidays</strong>: Calculated at <strong>3.0× multiplier</strong>.</p>
                </div>
            </div>
        </div>

        <!-- Multiplier Matrix Table -->
        <h3 class="text-xs font-extrabold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-3">Overtime Multipliers & Limits</h3>
        <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-700 mb-6">
            <table class="w-full text-xs sm:text-sm text-left text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 uppercase font-bold text-[11px] border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="px-5 py-3.5">Day Category</th>
                        <th class="px-5 py-3.5">Allowed Shift Window</th>
                        <th class="px-5 py-3.5">Rate Multiplier</th>
                        <th class="px-5 py-3.5">Daily & Monthly Limits</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 font-medium">
                    <tr>
                        <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white">Working Day (Mon – Fri)</td>
                        <td class="px-5 py-3.5 font-mono">17:00 – 21:00 (5:00 PM – 9:00 PM)</td>
                        <td class="px-5 py-3.5 font-mono font-bold text-indigo-600 dark:text-sky-400">1.50×</td>
                        <td class="px-5 py-3.5">Max 4.0h/day • Max 60h/month</td>
                    </tr>
                    <tr>
                        <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white">Weekend (Saturday / Sunday)</td>
                        <td class="px-5 py-3.5 font-mono">09:00 – 17:00 (9:00 AM – 5:00 PM)</td>
                        <td class="px-5 py-3.5 font-mono font-bold text-amber-600 dark:text-amber-400">2.00×</td>
                        <td class="px-5 py-3.5">Max 4.0h/day • Max 60h/month</td>
                    </tr>
                    <tr>
                        <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white">Myanmar Public Holiday</td>
                        <td class="px-5 py-3.5 font-mono">09:00 – 17:00 (9:00 AM – 5:00 PM)</td>
                        <td class="px-5 py-3.5 font-mono font-bold text-rose-600 dark:text-rose-400">3.00×</td>
                        <td class="px-5 py-3.5">Max 4.0h/day • Max 60h/month</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Overtime Calculation Formula -->
        <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-700">
            <div class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2 flex items-center gap-2">
                <i class="fa-solid fa-calculator text-amber-500"></i> Overtime Compensation Formula
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white dark:bg-slate-800 p-3.5 rounded-xl border border-slate-200 dark:border-slate-700">
                    <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400">1. Standard Hourly Wage</div>
                    <div class="font-mono text-sm font-bold text-slate-900 dark:text-white mt-1">Hourly Rate = (Basic Salary ÷ 30) ÷ 8</div>
                </div>
                <div class="bg-white dark:bg-slate-800 p-3.5 rounded-xl border border-slate-200 dark:border-slate-700">
                    <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400">2. Overtime Pay</div>
                    <div class="font-mono text-sm font-bold text-emerald-600 dark:text-emerald-400 mt-1">OT Pay = OT Hours × Hourly Rate × Multiplier</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- SECTION 5: FINAL TAKE-HOME SALARY FORMULA  -->
    <!-- ========================================== -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 lg:p-8 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
            <div class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-violet-950/60 border border-violet-100 dark:border-violet-800 text-violet-600 dark:text-violet-400 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-money-check-dollar"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white font-outfit">5. Total Compensation & Net Salary Formula</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Summary formula outlining how gross earnings and total deductions yield the final take-home salary.</p>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 text-white shadow-xl space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20">
                    <div class="text-xs uppercase font-bold text-cyan-300">Gross Salary</div>
                    <div class="font-mono text-sm sm:text-base font-bold mt-1">
                        Gross Pay = Basic Salary + Overtime Pay + Bonuses
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20">
                    <div class="text-xs uppercase font-bold text-rose-300">Total Deductions</div>
                    <div class="font-mono text-sm sm:text-base font-bold mt-1">
                        Deductions = Late Ded + Half-Day Ded + Full-Day Ded
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-white/20 flex flex-col sm:flex-row items-center justify-between gap-3">
                <span class="text-sm font-bold text-slate-200">Final Take-Home Net Salary:</span>
                <span class="font-mono text-base sm:text-xl font-black text-yellow-300 bg-white/10 px-4 py-2 rounded-xl border border-yellow-400/40">
                    Net Salary = Gross Salary − Total Deductions
                </span>
            </div>
        </div>
    </div>

</div>
