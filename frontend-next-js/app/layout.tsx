import type { Metadata } from 'next'
import { Sora } from 'next/font/google'
import './globals.css'
import Provider from './provider'

export const revalidate = 60;

const sora = Sora({ subsets: ['latin'] })

export const metadata: Metadata = {
  title: 'Do I Code',
  description: 'Inzicht op voorgang binnen GitHub repositories.',
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="en">
      <Provider>
        <body className={sora.className}>
          {children}
        </body>
      </Provider>
    </html>
  )
}
