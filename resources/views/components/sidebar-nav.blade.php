<nav class="flex flex-1 flex-col">
    <ul role="list" class="flex flex-1 flex-col gap-y-4 sm:gap-y-7">
        <li>
            <ul role="list" class="-mx-2 space-y-1 sm:space-y-1">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('dashboard') }}" 
                       class="group flex gap-x-3 rounded-md p-3 sm:p-2 text-sm sm:text-sm leading-6 font-semibold transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 border-r-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 shrink-0 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" 
                             fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        <span class="truncate">Dashboard</span>
                        @if(request()->routeIs('dashboard'))
                            <div class="ml-auto w-1.5 h-1.5 bg-indigo-600 rounded-full"></div>
                        @endif
                    </a>
                </li>

                <!-- Transactions -->
                <li>
                    <a href="{{ route('transactions') }}" 
                       class="group flex gap-x-3 rounded-md p-3 sm:p-2 text-sm sm:text-sm leading-6 font-semibold transition-colors duration-200 {{ request()->routeIs('transactions*') ? 'bg-indigo-50 text-indigo-600 border-r-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 shrink-0 {{ request()->routeIs('transactions*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" 
                             fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="truncate">Transaksi</span>
                        @if(request()->routeIs('transactions*'))
                            <div class="ml-auto w-1.5 h-1.5 bg-indigo-600 rounded-full"></div>
                        @endif
                    </a>
                </li>

                <!-- Categories -->
                <li>
                    <a href="{{ route('categories') }}" 
                       class="group flex gap-x-3 rounded-md p-3 sm:p-2 text-sm sm:text-sm leading-6 font-semibold transition-colors duration-200 {{ request()->routeIs('categories*') ? 'bg-indigo-50 text-indigo-600 border-r-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 shrink-0 {{ request()->routeIs('categories*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" 
                             fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                        </svg>
                        <span class="truncate">Kategori</span>
                        @if(request()->routeIs('categories*'))
                            <div class="ml-auto w-1.5 h-1.5 bg-indigo-600 rounded-full"></div>
                        @endif
                    </a>
                </li>

                <!-- Reports -->
                <li>
                    <a href="{{ route('reports') }}" 
                       class="group flex gap-x-3 rounded-md p-3 sm:p-2 text-sm sm:text-sm leading-6 font-semibold transition-colors duration-200 {{ request()->routeIs('reports*') ? 'bg-indigo-50 text-indigo-600 border-r-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 shrink-0 {{ request()->routeIs('reports*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" 
                             fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 7.996 21 8.625 21h6.75c.629 0 1.125-.504 1.125-1.125v-6.75c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v6.75C21 20.496 20.496 21 19.875 21H4.125C3.504 21 3 20.496 3 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-2.25c-.621 0-1.125-.504-1.125-1.125V8.625zM12 2.25c-.621 0-1.125.504-1.125 1.125v2.25c0 .621.504 1.125 1.125 1.125h2.25c.621 0 1.125-.504 1.125-1.125V3.375c0-.621-.504-1.125-1.125-1.125H12z" />
                        </svg>
                        <span class="truncate">Laporan</span>
                        @if(request()->routeIs('reports*'))
                            <div class="ml-auto w-1.5 h-1.5 bg-indigo-600 rounded-full"></div>
                        @endif
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>