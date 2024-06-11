'use client';
import React, { useState } from 'react';
import Link from "next/link";

export default function List({ data }: { data: any }) {
  const [showLoading, setShowLoading] = useState(false);
  const [entryLoading, setEntryLoading] = useState(0);
  return (
    <>
      <ul>
        {data.map((repository: any) =>
          (<li key={repository["id"]}><span className={"loading loading-spinner loading-xs" + (showLoading && (entryLoading === repository["id"]) ? " " : " hidden")}/> <Link onClick={() => { setShowLoading(true); setEntryLoading(repository["id"]); }} href={"/repo/" + repository["full_name"]}>{repository["full_name"]}</Link></li>)
        )}
      </ul>
    </>
  )
}