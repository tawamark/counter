import './bootstrap';

import Alpine from 'alpinejs';
import {
    AlertTriangle,
    ArrowDownUp,
    BarChart3,
    Check,
    ChevronDown,
    ClipboardList,
    Eye,
    LayoutDashboard,
    LogOut,
    Menu,
    Package,
    Pencil,
    Plus,
    Save,
    ScanLine,
    Search,
    Settings,
    Smartphone,
    Tags,
    Trash2,
    Truck,
    Users,
    X,
    createIcons,
} from 'lucide';

const icons = {
    AlertTriangle,
    ArrowDownUp,
    BarChart3,
    Check,
    ChevronDown,
    ClipboardList,
    Eye,
    LayoutDashboard,
    LogOut,
    Menu,
    Package,
    Pencil,
    Plus,
    Save,
    ScanLine,
    Search,
    Settings,
    Smartphone,
    Tags,
    Trash2,
    Truck,
    Users,
    X,
};

window.Alpine = Alpine;

Alpine.start();

window.createLucideIcons = () => createIcons({ icons });

window.createLucideIcons();
