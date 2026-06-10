import './bootstrap';

import Alpine from 'alpinejs';
import {
    AlertTriangle,
    ArrowDownUp,
    BarChart3,
    BadgeCheck,
    Check,
    ChevronDown,
    CircleCheck,
    ClipboardList,
    Coins,
    Download,
    Eye,
    FileDown,
    LayoutDashboard,
    LogOut,
    Menu,
    Package,
    Pencil,
    Plus,
    Save,
    ScanLine,
    Search,
    Scale,
    Settings,
    Smartphone,
    Tags,
    Trash2,
    TrendingDown,
    TrendingUp,
    Truck,
    Users,
    X,
    createIcons,
} from 'lucide';

const icons = {
    AlertTriangle,
    ArrowDownUp,
    BarChart3,
    BadgeCheck,
    Check,
    ChevronDown,
    CircleCheck,
    ClipboardList,
    Coins,
    Download,
    Eye,
    FileDown,
    LayoutDashboard,
    LogOut,
    Menu,
    Package,
    Pencil,
    Plus,
    Save,
    ScanLine,
    Search,
    Scale,
    Settings,
    Smartphone,
    Tags,
    Trash2,
    TrendingDown,
    TrendingUp,
    Truck,
    Users,
    X,
};

window.Alpine = Alpine;

Alpine.start();

window.createLucideIcons = () => createIcons({ icons });

window.createLucideIcons();
