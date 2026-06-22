import { BrowserRouter, Routes, Route, Outlet } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import { CartProvider } from './context/CartContext';
import ErrorBoundary from './components/ErrorBoundary';
import Navbar from './components/Navbar';
import Footer from './components/Footer';
import SupportChat from './components/SupportChat';
import Home from './pages/Home';
import ProductDetail from './pages/ProductDetail';
import Cart from './pages/Cart';
import Checkout from './pages/Checkout';
import Login from './pages/Login';
import Register from './pages/Register';
import Orders from './pages/Orders';
import AdminDashboard from './pages/AdminDashboard';
import AdminLayout from './components/AdminLayout';
import AdminProducts from './pages/AdminProducts';
import AdminCategories from './pages/AdminCategories';
import AdminBrands from './pages/AdminBrands';

import AdminOrders from './pages/AdminOrders';
import AdminCustomers from './pages/AdminCustomers';
import AdminSupport from './pages/AdminSupport';
import ContactSupport from './pages/ContactSupport';
import AboutUs from './pages/AboutUs';
import PrivacyPolicy from './pages/PrivacyPolicy';
import TermsOfService from './pages/TermsOfService';
import WarrantyPolicy from './pages/WarrantyPolicy';
import Intro from './pages/Intro';
import Sale from './pages/Sale';
import EditProfile from './pages/profile/EditProfile';
import ChangePassword from './pages/profile/ChangePassword';
import EditAvatar from './pages/profile/EditAvatar';

export default function App() {
    return (
        <BrowserRouter>
            <AuthProvider>
                <CartProvider>
                    <ErrorBoundary>
                        <Routes>
                            {/* Admin Routes */}
                            <Route path="/admin" element={<AdminLayout />}>
                                <Route path="dashboard" element={<AdminDashboard />} />
                                <Route path="products" element={<AdminProducts />} />
                                <Route path="categories" element={<AdminCategories />} />
                                <Route path="brands" element={<AdminBrands />} />
                                <Route path="orders" element={<AdminOrders />} />
                                <Route path="customers" element={<AdminCustomers />} />
                                <Route path="support" element={<AdminSupport />} />
                            </Route>

                            {/* Public/User Routes */}
                            <Route path="/" element={<Intro />} />
                            <Route element={
                                <div className="min-h-screen flex flex-col bg-canvas">
                                    <Navbar />
                                    <main className="flex-1">
                                        <Outlet />
                                    </main>
                                    <Footer />
                                    <SupportChat />
                                </div>
                            }>
                                <Route path="/home" element={<Home />} />
                                <Route path="/products/:slug" element={<ProductDetail />} />
                                <Route path="/cart" element={<Cart />} />
                                <Route path="/checkout" element={<Checkout />} />
                                <Route path="/login" element={<Login />} />
                                <Route path="/orders" element={<Orders />} />
                                <Route path="/register" element={<Register />} />
                                <Route path="/support" element={<ContactSupport />} />
                                <Route path="/about-us" element={<AboutUs />} />
                                <Route path="/privacy-policy" element={<PrivacyPolicy />} />
                                <Route path="/terms" element={<TermsOfService />} />
                                <Route path="/warranty" element={<WarrantyPolicy />} />
                                <Route path="/sale" element={<Sale />} />
                                <Route path="/profile" element={<EditProfile />} />
                                <Route path="/profile/password" element={<ChangePassword />} />
                                <Route path="/profile/avatar" element={<EditAvatar />} />
                            </Route>
                        </Routes>
                    </ErrorBoundary>
                </CartProvider>
            </AuthProvider>
        </BrowserRouter>
    );
}
