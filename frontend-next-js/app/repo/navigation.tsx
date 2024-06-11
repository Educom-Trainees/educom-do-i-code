'use client';

import React, { useState } from 'react';

import Image from 'next/image'
import Link from 'next/link';

import { MdHome, MdList } from "react-icons/md";
import { RiGitRepositoryFill } from "react-icons/ri";

import { useSession, signIn, signOut } from "next-auth/react"

export default function Navigation({repoFullName, currentRoute} : {repoFullName?: String, currentRoute?: String}) {
  const { data: session, status } = useSession();

  const [homeLoading, setHomeLoading] = useState(false);
  const [listLoading, setListLoading] = useState(false);
  const [repoLoading, setRepoLoading] = useState(false);

  return (
    <div className="navbar bg-base-100">
      <div className="navbar-start">
        <span className="text-xl">Do I Code</span>
      </div>

      <div className="navbar-center text-sm breadcrumbs max-w-[calc(100%-160px)] direction-rtl">
        <ul className="direction-ltr">
          <li>
            <Link onClick={() => (currentRoute != "/") && setHomeLoading(true)} className="inline-flex gap-1 items-center]" href="/">
              {homeLoading ? <span className="loading loading-spinner loading-xs"/> : <MdHome/>}
              Home
            </Link>
          </li>
          <li>
            <Link onClick={() => (currentRoute != "/repo/list") && setListLoading(true)} className="inline-flex gap-1 items-center" href="/repo/list">
              {listLoading ? <span className="loading loading-spinner loading-xs"/> : <MdList/>}
              Repositorylijst
            </Link>
          </li>
          {repoFullName &&
          <li>
            <Link onClick={() => (currentRoute != "/repo/" + repoFullName) && setRepoLoading(true)} href={"/repo/" + repoFullName} className="inline-flex gap-1 items-center">
              {repoLoading ? <span className="loading loading-spinner loading-xs"/> : <RiGitRepositoryFill/>}
              {repoFullName}
            </Link>
          </li>
          }
        </ul>
      </div>

      <div className="navbar-end">
        <div className="dropdown dropdown-end">
          <div tabIndex={0} role="button" className="ml-auto btn btn-ghost btn-circle avatar">
            <div className="w-10 rounded-full relative">
              {session ? <Image fill={true} alt={session?.user?.name!} src={session?.user?.image!} /> : <span className="loading loading-spinner loading-lg"/>}
            </div>
          </div>
          <ul tabIndex={0} className="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
            <li><Link href="https://github.com/settings">Instellingen</Link></li>
            <li><Link href="" onClick={() => signOut({ callbackUrl: '/' })}>Uitloggen</Link></li>
          </ul>
        </div>
      </div>
    </div>
  )
}