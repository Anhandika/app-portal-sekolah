/**
 * Firebase client (project anproject-8968f): Authentication Email/Password
 * + Cloud Firestore untuk web.
 *
 * Konfigurasi dibaca dari env Vite (lihat .env.example: VITE_FIREBASE_*).
 * Modul ini pasif bila VITE_FIREBASE_API_KEY kosong — tidak merusak boot app.
 *
 * Contoh pakai:
 *   import { firebaseSignIn, loginBackendViaFirebase } from './firebase';
 *   await firebaseSignIn(email, password);
 *   const { token } = await loginBackendViaFirebase('web');
 */
import { initializeApp } from 'firebase/app';
import {
    getAuth,
    createUserWithEmailAndPassword,
    signInWithEmailAndPassword,
    signOut,
    onAuthStateChanged,
} from 'firebase/auth';
import { getFirestore } from 'firebase/firestore';

const firebaseConfig = {
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID || 'anproject-8968f',
    storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
    messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
    appId: import.meta.env.VITE_FIREBASE_APP_ID,
};

let app = null;
let auth = null;
let db = null;

if (firebaseConfig.apiKey) {
    app = initializeApp(firebaseConfig);
    auth = getAuth(app);
    db = getFirestore(app);
} else {
    console.warn('[firebase] VITE_FIREBASE_API_KEY kosong — Firebase client nonaktif.');
}

export { app, auth, db };

export const firebaseSignUp = (email, password) => createUserWithEmailAndPassword(auth, email, password);
export const firebaseSignIn = (email, password) => signInWithEmailAndPassword(auth, email, password);
export const firebaseSignOut = () => signOut(auth);
export const onFirebaseAuthState = (cb) => onAuthStateChanged(auth, cb);

export const firebaseIdToken = async (forceRefresh = false) => {
    const user = auth ? auth.currentUser : null;
    return user ? user.getIdToken(forceRefresh) : null;
};

/** Tukar ID token Firebase menjadi Sanctum token backend (POST /api/auth/firebase). */
export async function loginBackendViaFirebase(deviceName = 'web') {
    const idToken = await firebaseIdToken(true);
    if (!idToken) {
        throw new Error('Belum login Firebase.');
    }
    const res = await fetch('/api/auth/firebase', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify({ id_token: idToken, device_name: deviceName }),
    });
    if (!res.ok) {
        throw new Error('Login backend via Firebase gagal (' + res.status + ').');
    }
    return res.json();
}