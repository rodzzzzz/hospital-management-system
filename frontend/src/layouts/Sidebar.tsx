import { useState } from 'react';
import { NavLink, useLocation } from 'react-router-dom';
import { useRBAC } from '@/rbac/useRBAC';
import { cn } from '@/lib/utils';
import {
  Home,
  Users,
  FileText,
  Hospital,
  FlaskConical,
  CreditCard,
  Pill,
  Tags,
  Package,
  UtensilsCrossed,
  Banknote,
  MessageSquare,
  UserCog,
  ShieldCheck,
  ChevronLeft,
  ChevronRight,
  Activity,
  Stethoscope,
  MonitorDot,
  Radiation,
  Syringe,
  BedDouble,
  Truck,
  Baby,
  LayoutDashboard,
} from 'lucide-react';
import type { ReactNode } from 'react';

interface NavItem {
  href: string;
  label: string;
  icon: ReactNode;
  modules?: string[];
  children?: NavItem[];
}

const navItems: NavItem[] = [
  {
    href: '/dashboard',
    label: 'Dashboard',
    icon: <Home size={20} />,
  },
  {
    href: '/patients',
    label: 'Patients',
    icon: <Users size={20} />,
  },
  {
    href: '/medical-records',
    label: 'Medical Records',
    icon: <FileText size={20} />,
  },
  {
    href: '/clinical-area',
    label: 'Clinical Area',
    icon: <Hospital size={20} />,
    modules: ['ER', 'OPD', 'DOCTOR', 'ICU', 'XRAY'],
    children: [
      { href: '/opd', label: 'OPD', icon: <Stethoscope size={18} />, modules: ['OPD'] },
      { href: '/er', label: 'Emergency Room', icon: <Activity size={18} />, modules: ['ER'] },
      { href: '/operating-room', label: 'Operating Room', icon: <MonitorDot size={18} />, modules: ['DOCTOR'] },
      { href: '/delivery-room', label: 'Delivery Room', icon: <Baby size={18} />, modules: ['DOCTOR'] },
      { href: '/icu', label: 'ICU', icon: <BedDouble size={18} />, modules: ['ICU'] },
      { href: '/xray', label: 'X-Ray', icon: <Radiation size={18} />, modules: ['XRAY'] },
    ],
  },
  {
    href: '/dialysis',
    label: 'Dialysis',
    icon: <Syringe size={20} />,
    modules: ['LAB'],
  },
  {
    href: '/laboratory',
    label: 'Laboratory',
    icon: <FlaskConical size={20} />,
    modules: ['LAB'],
  },
  {
    href: '/cashier',
    label: 'Cashier',
    icon: <CreditCard size={20} />,
    modules: ['CASHIER'],
  },
  {
    href: '/pharmacy',
    label: 'Pharmacy',
    icon: <Pill size={20} />,
    modules: ['PHARMACY'],
  },
  {
    href: '/price-master',
    label: 'Price Master',
    icon: <Tags size={20} />,
  },
  {
    href: '/inventory',
    label: 'Inventory',
    icon: <Package size={20} />,
  },
  {
    href: '/kitchen',
    label: 'Kitchen',
    icon: <UtensilsCrossed size={20} />,
  },
  {
    href: '/payroll',
    label: 'Payroll',
    icon: <Banknote size={20} />,
  },
  {
    href: '/chat',
    label: 'Chat Messages',
    icon: <MessageSquare size={20} />,
  },
  {
    href: '/hr',
    label: 'HR',
    icon: <UserCog size={20} />,
    modules: ['HR'],
  },
  {
    href: '/philhealth',
    label: 'PhilHealth Claims',
    icon: <ShieldCheck size={20} />,
  },
  {
    href: '/displays',
    label: 'Display Screens',
    icon: <LayoutDashboard size={20} />,
  },
  {
    href: '/queue',
    label: 'Queue Dashboard',
    icon: <Truck size={20} />,
  },
];

const alwaysVisible = ['/dashboard', '/patients', '/chat'];

export function Sidebar() {
  const [collapsed, setCollapsed] = useState(false);
  const { isAdmin, hasAnyModule } = useRBAC();
  const location = useLocation();

  function isVisible(item: NavItem): boolean {
    if (alwaysVisible.includes(item.href)) return true;
    if (isAdmin) return true;
    if (!item.modules || item.modules.length === 0) return isAdmin;
    return hasAnyModule(item.modules);
  }

  function isActive(item: NavItem): boolean {
    if (location.pathname === item.href) return true;
    if (item.children) {
      return item.children.some((c) => location.pathname.startsWith(c.href));
    }
    return location.pathname.startsWith(item.href + '/');
  }

  const filteredNav = navItems.filter(isVisible);

  return (
    <aside
      className={cn(
        'fixed inset-y-0 left-0 z-40 flex flex-col bg-white border-r border-gray-200 transition-all duration-300',
        collapsed ? 'w-16' : 'w-64'
      )}
    >
      {/* Logo */}
      <div className="flex items-center h-16 px-4 border-b border-gray-200">
        {!collapsed && (
          <span className="text-lg font-bold text-blue-800 truncate">
            Hospital HMS
          </span>
        )}
        <button
          onClick={() => setCollapsed(!collapsed)}
          className={cn(
            'p-1.5 rounded-lg hover:bg-gray-100 text-gray-500',
            collapsed ? 'mx-auto' : 'ml-auto'
          )}
        >
          {collapsed ? <ChevronRight size={18} /> : <ChevronLeft size={18} />}
        </button>
      </div>

      {/* Navigation */}
      <nav className="flex-1 overflow-y-auto py-2 px-2">
        {filteredNav.map((item) => (
          <SidebarItem
            key={item.href}
            item={item}
            collapsed={collapsed}
            active={isActive(item)}
            isVisible={isVisible}
          />
        ))}
      </nav>
    </aside>
  );
}

function SidebarItem({
  item,
  collapsed,
  active,
  isVisible,
}: {
  item: NavItem;
  collapsed: boolean;
  active: boolean;
  isVisible: (item: NavItem) => boolean;
}) {
  const [open, setOpen] = useState(active);

  if (item.children) {
    const visibleChildren = item.children.filter(isVisible);
    if (visibleChildren.length === 0) return null;

    return (
      <div className="mb-1">
        <button
          onClick={() => setOpen(!open)}
          className={cn(
            'flex items-center w-full gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors',
            active
              ? 'bg-blue-50 text-blue-700'
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
          )}
        >
          <span className="flex-shrink-0">{item.icon}</span>
          {!collapsed && (
            <>
              <span className="flex-1 text-left truncate">{item.label}</span>
              <ChevronRight
                size={14}
                className={cn('transition-transform', open && 'rotate-90')}
              />
            </>
          )}
        </button>
        {!collapsed && open && (
          <div className="ml-4 mt-1 space-y-0.5">
            {visibleChildren.map((child) => (
              <NavLink
                key={child.href}
                to={child.href}
                className={({ isActive: linkActive }) =>
                  cn(
                    'flex items-center gap-3 px-3 py-1.5 rounded-lg text-sm transition-colors',
                    linkActive
                      ? 'bg-blue-50 text-blue-700 font-medium'
                      : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700'
                  )
                }
              >
                <span className="flex-shrink-0">{child.icon}</span>
                <span className="truncate">{child.label}</span>
              </NavLink>
            ))}
          </div>
        )}
      </div>
    );
  }

  return (
    <NavLink
      to={item.href}
      className={({ isActive: linkActive }) =>
        cn(
          'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors mb-0.5',
          linkActive
            ? 'bg-blue-50 text-blue-700'
            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
        )
      }
      title={collapsed ? item.label : undefined}
    >
      <span className="flex-shrink-0">{item.icon}</span>
      {!collapsed && <span className="truncate">{item.label}</span>}
    </NavLink>
  );
}
