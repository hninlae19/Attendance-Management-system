<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4" data-aos="fade-down">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Notifications</h1>
        <p class="text-gray-500 text-sm mt-1">View system alerts and pending approvals.</p>
    </div>
    <button class="text-sm font-bold text-primary bg-primary/10 hover:bg-primary/20 px-4 py-2 rounded-lg transition-colors">
        Mark all as read
    </button>
</div>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 dark:border-gray-700 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
        <!-- New Leave Request -->
        <li class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer relative overflow-hidden group">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary group-hover:w-1.5 transition-all"></div>
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-calendar-plus text-lg"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-1">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">New Leave Request</h4>
                        <span class="text-xs font-medium text-primary bg-primary/10 px-2 py-0.5 rounded-md">New</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2"><strong>Jane Doe</strong> has requested 3 days of Annual Leave.</p>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span class="flex items-center"><i class="fa-regular fa-clock mr-1"></i> 10 minutes ago</span>
                    </div>
                </div>
                <button class="w-8 h-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center justify-center text-gray-400 transition-colors">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
            </div>
        </li>

        <!-- Overtime Approval -->
        <li class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer relative overflow-hidden group">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary group-hover:w-1.5 transition-all"></div>
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-business-time text-lg"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-1">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Overtime Approval Pending</h4>
                        <span class="text-xs font-medium text-primary bg-primary/10 px-2 py-0.5 rounded-md">New</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2"><strong>John Smith</strong> logged 4 hours of weekend overtime.</p>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span class="flex items-center"><i class="fa-regular fa-clock mr-1"></i> 1 hour ago</span>
                    </div>
                </div>
                <button class="w-8 h-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center justify-center text-gray-400 transition-colors">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
            </div>
        </li>

        <!-- Read Notification -->
        <li class="p-6 bg-gray-50/50 dark:bg-gray-800/50 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer">
            <div class="flex items-start gap-4 opacity-75">
                <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-file-invoice-dollar text-lg"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-1">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300">Payroll Processed</h4>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Payroll for the month of October has been generated successfully.</p>
                    <div class="flex items-center gap-4 text-xs text-gray-400">
                        <span class="flex items-center"><i class="fa-regular fa-clock mr-1"></i> 2 days ago</span>
                    </div>
                </div>
                <button class="w-8 h-8 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center text-gray-400 transition-colors">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
            </div>
        </li>
    </ul>
    
    <div class="p-4 border-t border-gray-100 dark:border-gray-700 text-center bg-gray-50/50 dark:bg-gray-800/50">
        <button class="text-sm font-bold text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
            Load More Notifications
        </button>
    </div>
</div>
