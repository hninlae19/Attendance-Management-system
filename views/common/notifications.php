<div x-data="notificationHistory()" class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4" data-aos="fade-down">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notification History</h1>
            <p class="text-gray-500 text-sm mt-1">View and manage all your system notifications.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="markAllAsRead()" class="flex items-center px-4 py-2 text-sm font-medium text-primary bg-primary/10 rounded-lg hover:bg-primary/20 transition-colors">
                <i class="fa-solid fa-check-double mr-2"></i> Mark All as Read
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4" data-aos="fade-up" data-aos-delay="50">
        <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
            
            <!-- Category Tabs -->
            <div class="flex space-x-1 bg-gray-100 dark:bg-gray-700/50 p-1 rounded-lg overflow-x-auto max-w-full">
                <button @click="filterType = ''" :class="filterType === '' ? 'bg-white dark:bg-gray-600 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'" class="px-4 py-1.5 text-sm font-medium rounded-md whitespace-nowrap transition-all">All</button>
                <button @click="filterType = 'attendance'" :class="filterType === 'attendance' ? 'bg-white dark:bg-gray-600 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'" class="px-4 py-1.5 text-sm font-medium rounded-md whitespace-nowrap transition-all">Attendance</button>
                <button @click="filterType = 'leave'" :class="filterType === 'leave' ? 'bg-white dark:bg-gray-600 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'" class="px-4 py-1.5 text-sm font-medium rounded-md whitespace-nowrap transition-all">Leave</button>
                <button @click="filterType = 'overtime'" :class="filterType === 'overtime' ? 'bg-white dark:bg-gray-600 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'" class="px-4 py-1.5 text-sm font-medium rounded-md whitespace-nowrap transition-all">Overtime</button>
                <button @click="filterType = 'payroll'" :class="filterType === 'payroll' ? 'bg-white dark:bg-gray-600 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'" class="px-4 py-1.5 text-sm font-medium rounded-md whitespace-nowrap transition-all">Payroll</button>
                <button @click="filterType = 'system'" :class="filterType === 'system' ? 'bg-white dark:bg-gray-600 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'" class="px-4 py-1.5 text-sm font-medium rounded-md whitespace-nowrap transition-all">System</button>
            </div>

            <!-- Search -->
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input type="text" x-model="searchQuery" placeholder="Search notifications..." class="w-full pl-10 px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
            </div>

        </div>
    </div>

    <!-- Notification List -->
    <div class="space-y-4" data-aos="fade-up" data-aos-delay="100">
        <template x-if="filteredNotifications.length === 0">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                    <i class="fa-regular fa-bell-slash text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No notifications found</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">You don't have any notifications matching this filter.</p>
            </div>
        </template>

        <template x-for="notif in paginatedNotifications" :key="notif.id">
            <div 
                @click="viewNotification(notif)"
                class="group relative bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-5 cursor-pointer transition-all duration-300 hover:shadow-md"
                :class="notif.is_read == 0 ? 'border-primary/30 bg-indigo-50/50 dark:bg-indigo-900/20 dark:border-indigo-500/30' : 'border-gray-100 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'"
            >
                <div class="flex gap-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white shadow-sm"
                             :class="getIconColor(notif.type)">
                            <i class="fa-solid" :class="getIcon(notif.type)"></i>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="text-sm font-bold truncate pr-4" :class="notif.is_read == 0 ? 'text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300'" x-text="notif.title || 'System Notification'"></h4>
                            <span class="text-xs whitespace-nowrap text-gray-500 flex items-center">
                                <i class="fa-regular fa-clock mr-1"></i>
                                <span x-text="formatDate(notif.created_at)"></span>
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-2" x-text="notif.message"></p>
                        
                        <div class="flex items-center text-xs text-gray-500">
                            <span class="bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded capitalize mr-3" x-text="notif.type"></span>
                            <span x-show="notif.sender_name"><i class="fa-solid fa-user mr-1"></i> <span x-text="notif.sender_name"></span></span>
                            <span x-show="!notif.sender_name"><i class="fa-solid fa-robot mr-1"></i> System</span>
                        </div>
                    </div>
                    
                    <!-- Unread Dot -->
                    <div class="flex-shrink-0 flex items-center justify-center w-4">
                        <div x-show="notif.is_read == 0" class="w-2.5 h-2.5 bg-primary rounded-full shadow-[0_0_8px_rgba(79,70,229,0.5)]"></div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Pagination -->
    <div x-show="filteredNotifications.length > itemsPerPage" class="flex justify-between items-center bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <span class="text-sm text-gray-600 dark:text-gray-400">
            Showing <span class="font-bold text-gray-900 dark:text-white" x-text="startIndex + 1"></span> to <span class="font-bold text-gray-900 dark:text-white" x-text="Math.min(endIndex, filteredNotifications.length)"></span> of <span class="font-bold text-gray-900 dark:text-white" x-text="filteredNotifications.length"></span>
        </span>
        <div class="inline-flex rounded-md shadow-sm">
            <button @click="currentPage--" :disabled="currentPage === 1" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white disabled:opacity-50">Prev</button>
            <button @click="currentPage++" :disabled="currentPage === totalPages" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-l-0 border-gray-200 rounded-r-lg hover:bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white disabled:opacity-50">Next</button>
        </div>
    </div>

    <!-- Notification Details Modal -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[60] overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="modalOpen = false" class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full shadow-2xl overflow-hidden transform transition-all" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/80 dark:bg-gray-800/80">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                    Notification Details
                </h3>
                <button @click="modalOpen = false" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30">
                    <i class="fa-solid fa-xmark text-xl w-6 h-6 flex items-center justify-center"></i>
                </button>
            </div>

            <div class="p-6" x-if="selectedNotif">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-white shadow-sm text-xl"
                         :class="getIconColor(selectedNotif?.type)">
                        <i class="fa-solid" :class="getIcon(selectedNotif?.type)"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white leading-tight" x-text="selectedNotif?.title || 'System Notification'"></h4>
                        <div class="text-xs text-gray-500 mt-1 flex gap-3">
                            <span class="capitalize bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded" x-text="selectedNotif?.type"></span>
                            <span class="flex items-center"><i class="fa-regular fa-clock mr-1"></i> <span x-text="formatDate(selectedNotif?.created_at)"></span></span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700 mb-6">
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed" x-text="selectedNotif?.message"></p>
                </div>

                <div class="flex items-center justify-between text-sm border-t border-gray-100 dark:border-gray-700 pt-4">
                    <span class="text-gray-500 dark:text-gray-400">Sent by:</span>
                    <div class="flex items-center font-medium text-gray-900 dark:text-white">
                        <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center mr-2 text-[10px]">
                            <i class="fa-solid fa-user text-gray-500 dark:text-gray-400"></i>
                        </div>
                        <span x-text="selectedNotif?.sender_name || 'System Auto-Generated'"></span>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-end bg-gray-50/50 dark:bg-gray-800/50 gap-3" x-if="selectedNotif">
                <a :href="selectedNotif?.link !== '#' ? '/payrollsystem' + selectedNotif?.link : '#'" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 transition-all shadow-sm flex-1 text-center" x-show="selectedNotif?.link && selectedNotif?.link !== '#'">
                    View Record <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
                <button @click="modalOpen = false" class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all shadow-sm">
                    Close
                </button>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('notificationHistory', () => ({
        notifications: <?= json_encode($data['notifications']) ?>,
        filterType: '<?= $data['current_type'] ?? '' ?>',
        searchQuery: '',
        currentPage: 1,
        itemsPerPage: 10,
        modalOpen: false,
        selectedNotif: null,

        get filteredNotifications() {
            return this.notifications.filter(n => {
                const matchesType = this.filterType === '' || n.type === this.filterType;
                const matchesSearch = this.searchQuery === '' || 
                    n.message.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                    (n.title && n.title.toLowerCase().includes(this.searchQuery.toLowerCase()));
                return matchesType && matchesSearch;
            });
        },

        get totalPages() {
            return Math.ceil(this.filteredNotifications.length / this.itemsPerPage) || 1;
        },

        get startIndex() {
            return (this.currentPage - 1) * this.itemsPerPage;
        },

        get endIndex() {
            return this.startIndex + this.itemsPerPage;
        },

        get paginatedNotifications() {
            return this.filteredNotifications.slice(this.startIndex, this.endIndex);
        },

        init() {
            this.$watch('filterType', () => { this.currentPage = 1; });
            this.$watch('searchQuery', () => { this.currentPage = 1; });
        },

        getIcon(type) {
            switch(type) {
                case 'attendance': return 'fa-clock-rotate-left';
                case 'leave': return 'fa-plane';
                case 'overtime': return 'fa-bolt';
                case 'payroll': return 'fa-money-check-dollar';
                case 'error': return 'fa-triangle-exclamation';
                default: return 'fa-bell';
            }
        },

        getIconColor(type) {
            switch(type) {
                case 'attendance': return 'bg-blue-500';
                case 'leave': return 'bg-emerald-500';
                case 'overtime': return 'bg-orange-500';
                case 'payroll': return 'bg-cyan-500';
                case 'error': return 'bg-red-500';
                default: return 'bg-gray-500';
            }
        },

        formatDate(dateString) {
            if (!dateString) return '';
            const d = new Date(dateString);
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + ' at ' + 
                   d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
        },

        async markAllAsRead() {
            try {
                await fetch('/payrollsystem/notification/api?action=read_all', {method: 'POST'});
                this.notifications = this.notifications.map(n => ({...n, is_read: 1}));
                // Tell layout to update badge
                window.dispatchEvent(new CustomEvent('notifications-read'));
            } catch(e) { console.error(e); }
        },

        async viewNotification(notif) {
            this.selectedNotif = notif;
            this.modalOpen = true;
            if (notif.is_read == 0) {
                try {
                    await fetch('/payrollsystem/notification/api?action=read', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({id: notif.id})
                    });
                    const idx = this.notifications.findIndex(n => n.id === notif.id);
                    if (idx > -1) this.notifications[idx].is_read = 1;
                    window.dispatchEvent(new CustomEvent('notifications-read'));
                } catch(e) { console.error(e); }
            }
        }
    }));
});
</script>
