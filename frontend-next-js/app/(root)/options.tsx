'use client';
import React, { useState } from 'react';
import { useSession, signIn, signOut } from "next-auth/react"
import Link from "next/link";

export default function Options() {
  const { data: session, status } = useSession();
  const [loading, setLoading] = useState(false);

  if (status === "loading") {
    return <span className="loading loading-spinner loading-lg"></span>
  } else if (status === "unauthenticated") {
    return (
      <Link href="" className="btn btn-primary" onClick={() => { setLoading(true); signIn(); }}>
        {loading && <span className="loading loading-spinner"></span>}
        Inloggen
      </Link>
    )
  } else if (status === "authenticated") {
    return (
      <div>
        <Link className="btn btn-primary" onClick={() => setLoading(true)} href="/repo/list">
          {loading && <span className="loading loading-spinner"></span>}
          Bekijk Repositories
        </Link>
        <br/>
        {session && <small>Je bent op dit moment ingelogd als {session && session?.user?.name}. <Link href="" onClick={() => signOut()}>Uitloggen?</Link></small>}
      </div>
    )
  } else {
    return <span>Er is een fout opgetreden. Probeer het later opnieuw.</span>
  }
}