import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Link, useForm, usePage } from '@inertiajs/react';
import { Transition } from '@headlessui/react';

export default function UpdateProfileInformation({ mustVerifyEmail, status, className = '' }) {
    const user = usePage().props.auth.user;

    const { data, setData, patch, errors, processing, recentlySuccessful } = useForm({
        name: user.name,
        email: user.email,
        birthdate: user.birthdate,
    });

    const submit = (e) => {
        e.preventDefault();
        patch(route('profile.update'));
    };

    return (
        <section className={className}>
            <header>
                <h2 className="text-lg font-medium text-gray-900">Profil információk</h2>

                <p className="mt-1 text-sm text-gray-600">
                    Frissítse fiókja profiladatait és e-mail címét.
                </p>
            </header>

            <form onSubmit={submit} className="mt-6 space-y-6">
                <div>
                    <InputLabel htmlFor="name" value="Név" />

                    <TextInput
                        id="name"
                        className="mt-1 block w-full"
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                        required
                        isFocused
                        autoComplete="name"
                    />

                    <InputError className="mt-2" message={errors.name} />
                </div>

                <div>
                    <InputLabel htmlFor="email" value="E-mail" />
                    <span className='block my-2'>{ data.email }</span>
                </div>

                <div>
                    <InputLabel htmlFor="birthdate" value="Születési dátum" />

                    <TextInput
                        id="birthdate"
                        type="date"
                        name="birthdate"
                        max={ new Date(Date.now() - 86400000).toJSON().slice(0, 10) }
                        value={ data.birthdate }
                        className="mt-1 block w-full"
                        autoComplete="new-birthdate"
                        onChange={ (e) => setData('birthdate', e.target.value) }
                        required
                    />

                    <InputError className="mt-2" message={errors.birthdate} />
                </div>

                {mustVerifyEmail && user.email_verified_at === null && (
                    <div>
                        <p className="text-sm mt-2 text-gray-800">
                            Az Ön e-mail címe nincs ellenőrizve.
                            <Link
                                href={route('verification.send')}
                                method="post"
                                as="button"
                                className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Kattintson ide az ellenőrző e-mail újbóli elküldéséhez.
                            </Link>
                        </p>

                        {status === 'verification-link-sent' && (
                            <div className="mt-2 font-medium text-sm text-green-600">
                                Új ellenőrző linket küldtünk az e-mail címére.
                            </div>
                        )}
                    </div>
                )}

                <div className="flex items-center gap-4">
                    <PrimaryButton disabled={processing}>Mentés</PrimaryButton>

                    <Transition
                        show={recentlySuccessful}
                        enter="transition ease-in-out"
                        enterFrom="opacity-0"
                        leave="transition ease-in-out"
                        leaveTo="opacity-0"
                    >
                        <p className="text-sm text-gray-600">Sikeresen mentve.</p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}
